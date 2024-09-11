@props(['post'])
<div class="grid sm:grid-cols-2 gap-y-5 gap-x-10">
    <div class="md:h-[400px] w-full overflow-hidden rounded-xl bg-zinc-300">
        <img class="flex items-center justify-center object-contain object-top w-full h-full md:object-cover" src="{{ asset($post->featurePhoto) }}" alt="{{ $post->photo_alt_text }}">
    </div>
    <div class="flex flex-col justify-center py-4 space-y-10 sm:pl-10">
        <div>
            <div class="mb-5">
                <a href="{{ route('filamentblog.post.show', ['post' => $post->slug]) }}" class="block mb-4 text-xl font-semibold md:text-4xl hover:text-blue-600">
                    {{ $post->title }}
                </a>
                <div>
                    @foreach ($post->categories as $category)
                    <a href="{{ route('filamentblog.category.post', ['category' => $category->slug]) }}">
                        <span class="inline-flex px-2 py-1 mr-2 text-xs font-semibold rounded-full bg-primary-200 text-primary-800">{{ $category->name }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
            <p class="mb-4">
                {!! Str::limit($post->sub_title) !!}
            </p>
        </div>
        <div class="flex items-center gap-4">
            @if($post->user)
                <img class="h-14 w-14 overflow-hidden rounded-full bg-zinc-300 object-cover md:object-fill text-[0]" src="{{ $post->user->avatar ?? asset('path/to/default-avatar.png') }}" alt="{{ $post->user->name() }}">
                <div>
                    <span title="{{ $post->user->name() }}" class="block max-w-[150px] overflow-hidden text-ellipsis whitespace-nowrap font-semibold">{{ $post->user->name() }}</span>
                    <span class="block text-sm font-medium font-semibold whitespace-nowrap text-zinc-600">
                        {{ $post->formattedPublishedDate() }}</span>
                </div>
            @else
                <div>
                    <span class="block max-w-[150px] overflow-hidden text-ellipsis whitespace-nowrap font-semibold">Unknown Author</span>
                    <span class="block text-sm font-medium font-semibold whitespace-nowrap text-zinc-600">
                        {{ $post->formattedPublishedDate() }}</span>
                </div>
            @endif
        </div>
    </div>
</div>