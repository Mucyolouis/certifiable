<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Attributes\Computed;
use App\Models\Church;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChurchBaptismPredictions extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.church-baptism-predictions';

    protected static ?string $title = 'Church Baptism Predictions';

    protected static ?string $navigationLabel = 'Church Baptism Rates';

    protected static ?int $navigationSort = 4;

    

    #[Computed]
    public function getChurchWithHighestBaptismRate(): ?Church
    {
        return Church::select('churches.id', 'churches.name')
            ->selectRaw('COUNT(users.id) as users_count')
            ->selectRaw('SUM(CASE WHEN users.baptized = 1 THEN 1 ELSE 0 END) as baptized_users_count')
            ->leftJoin('users', 'churches.id', '=', 'users.church_id')
            ->groupBy('churches.id', 'churches.name')
            ->get()
            ->sortByDesc(function ($church) {
                return $church->users_count > 0 
                    ? ($church->baptized_users_count / $church->users_count) * 100 
                    : 0;
            })
            ->first();
    }


    
    #[Computed]
    public function getChartData(): array
    {
        $churches = $this->getChurchesData();

        return [
            'labels' => $churches->pluck('name')->toArray(),
            'data' => $churches->pluck('percentage')->toArray(),
        ];
    }

    #[Computed]
    public function getTrendData(): array
    {
        $churches = $this->getChurchesData();
        
        $percentages = $churches->pluck('percentage')->toArray();
        $average = array_sum($percentages) / count($percentages);
        $median = $percentages[floor(count($percentages) / 2)];
        
        $maxChurch = $churches->sortByDesc('percentage')->first();
        $minChurch = $churches->sortBy('percentage')->first();
        
        $growthRate = 0;
        if ($churches->count() > 1) {
            $firstValue = $churches->first()['percentage'];
            $lastValue = $churches->last()['percentage'];
            $growthRate = ($lastValue - $firstValue) / $firstValue * 100;
        }

        return [
            'average' => round($average, 2),
            'median' => round($median, 2),
            'max' => [
                'name' => $maxChurch['name'],
                'value' => round($maxChurch['percentage'], 2)
            ],
            'min' => [
                'name' => $minChurch['name'],
                'value' => round($minChurch['percentage'], 2)
            ],
            'growthRate' => round($growthRate, 2)
        ];
    }

    private function getChurchesData()
    {
        return Church::select('churches.id', 'churches.name')
            ->selectRaw('COUNT(users.id) as total_members')
            ->selectRaw('SUM(CASE WHEN users.baptized = 1 THEN 1 ELSE 0 END) as baptized_members')
            ->leftJoin('users', 'churches.id', '=', 'users.church_id')
            ->groupBy('churches.id', 'churches.name')
            ->get()
            ->map(function ($church) {
                $percentage = $church->total_members > 0 
                    ? ($church->baptized_members / $church->total_members) * 100 
                    : 0;
                return [
                    'name' => $church->name,
                    'percentage' => round($percentage, 2)
                ];
            })
            ->sortBy('name')
            ->values();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Church::query())
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('total_members')
                    ->getStateUsing(fn (Church $record): int => $record->users()->count())
                    ->sortable(),
                TextColumn::make('baptized_members')
                    ->getStateUsing(fn (Church $record): int => $record->users()->where('baptized', 1)->count())
                    ->sortable(),
                TextColumn::make('baptism_percentage')
                    ->getStateUsing(function (Church $record): string {
                        $totalMembers = $record->users()->count();
                        $baptizedMembers = $record->users()->where('baptized', 1)->count();
                        $percentage = $totalMembers > 0 ? ($baptizedMembers / $totalMembers) * 100 : 0;
                        return number_format($percentage, 2) . '%';
                    })
                    ->sortable(),
            ])
            ->defaultSort('baptism_percentage', 'desc');
    }


    private function getChurchStats(Church $church): array
    {
        $totalChristians = $church->users()->count();
        $baptizedChristians = $church->users()->where('baptized', 1)->count();
        
        // Fetch pastor role
        $pastorRole = Role::findByName('pastor');
        
        // Count users with pastor role
        $totalPastors = $church->users()
            ->whereHas('roles', function($query) use ($pastorRole) {
                $query->where('role_id', $pastorRole->id);
            })
            ->count();
        
        $currentRate = $totalChristians > 0 ? ($baptizedChristians / $totalChristians) * 100 : 0;

        return [
            'total_christians' => $totalChristians,
            'baptized_christians' => $baptizedChristians,
            'total_pastors' => $totalPastors,
            'current_rate' => round($currentRate, 2)
        ];
    }

    #[Computed]
    public function getTop5FuturePredictions(): array
    {
        $churches = Church::all();
        $predictions = [];

        foreach ($churches as $church) {
            $stats = $this->getChurchStats($church);
            $prediction = $this->predictFutureRate($stats);
            
            $predictions[] = [
                'name' => $church->name,
                'current_rate' => $stats['current_rate'],
                'predicted_rate' => $prediction['predicted_rate'],
                'trend' => $prediction['trend'],
                'total_christians' => $stats['total_christians'],
                'baptized_christians' => $stats['baptized_christians'],
                'total_pastors' => $stats['total_pastors']
            ];
        }

        // Sort by predicted rate in descending order and take top 5
        usort($predictions, function($a, $b) {
            return $b['predicted_rate'] <=> $a['predicted_rate'];
        });

        return array_slice($predictions, 0, 5);
    }

    private function predictFutureRate(array $stats): array
    {
        // Simple prediction model
        $baptismPotential = $stats['total_christians'] - $stats['baptized_christians'];
        $pastorInfluence = $stats['total_pastors'] * 5; // Assuming each pastor can influence 5 baptisms

        $predictedNewBaptisms = min($baptismPotential, $pastorInfluence);
        $predictedTotalBaptized = $stats['baptized_christians'] + $predictedNewBaptisms;

        $predictedRate = $stats['total_christians'] > 0 
            ? ($predictedTotalBaptized / $stats['total_christians']) * 100 
            : 0;

        $trend = $predictedRate > $stats['current_rate'] ? 'Increasing' : 
                 ($predictedRate < $stats['current_rate'] ? 'Decreasing' : 'Stable');

        return [
            'predicted_rate' => round($predictedRate, 2),
            'trend' => $trend
        ];
    }
}