namespace App\Enums;

enum TransferReason: string
{
    case GeographicalRelocation = 'Geographical Relocation';
    case TheologicalDifferences = 'Theological Differences';
    case FamilyReasons = 'Family Reasons';
    case Work = 'Work';
    case ChurchLeadershipAndManagement = 'Church Leadership and Management';
    case Other = 'Other';

    public static function options(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }
}
