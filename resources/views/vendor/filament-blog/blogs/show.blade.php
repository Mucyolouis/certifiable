<x-blog-layout>
    <section class="pb-16">
        <div class="container mx-auto">
            <div class="flex mb-10 text-sm font-semibold gap-x-2">
                <a href="{{ route('filamentblog.post.index') }}" class="opacity-60">Home</a>
                <span class="opacity-30">/</span>
                <a href="{{ route('filamentblog.post.all') }}" class="opacity-60">Blog</a>
                <span class="opacity-30">/</span>
                <a title="{{ $post->slug }}" href="{{ route('filamentblog.post.show', ['post' => $post->slug]) }}" class="max-w-2xl font-medium truncate transition-all duration-300 hover:text-primary-600">
                    {{ $post->title }}
                </a>
            </div>
            <div class="mx-auto mb-20 space-y-10">
                <div class="grid gap-x-20 sm:grid-cols-[minmax(min-content,10%)_1fr_minmax(min-content,10%)]">
                    <div class="py-5">
                        <div class="sticky flex flex-col items-center divide-y-2 top-24 gap-y-5">
                            <button x-data="" x-on:click="document.getElementById('comments').scrollIntoView({ behavior: 'smooth'})" class="flex flex-col items-center justify-center group/btn gap-y-2">
                                <div class="flex items-center justify-center px-4 py-4 rounded-full bg-slate-100 group-hover/btn:bg-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M13 11H7a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2m4-4H7a1 1 0 0 0 0 2h10a1 1 0 0 0 0-2m2-5H5a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h11.59l3.7 3.71A1 1 0 0 0 21 22a.84.84 0 0 0 .38-.08A1 1 0 0 0 22 21V5a3 3 0 0 0-3-3m1 16.59l-2.29-2.3A1 1 0 0 0 17 16H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1Z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold">COMMENTS</span>
                            </button>
                            <div class="pt-5">
                                {!! $shareButton?->html_code !!}
                            </div>
                        </div>
                    </div>
                    <div class="space-y-10">
                        <div>
                            <div class="flex flex-col justify-end">
                                <div class="w-full h-full mb-6 overflow-hidden rounded bg-slate-200">
                                    <img class="flex h-full min-h-[400px] items-center justify-center object-cover object-top text-sm text-xl font-semibold text-slate-400" src="{{ $post->featurePhoto ?? asset('path/to/default-feature-image.png') }}" alt="{{ $post->photo_alt_text ?? 'Feature image' }}">
                                </div>
                                <div class="mb-6">
                                    <h1 class="mb-6 text-4xl font-semibold">
                                        {{ $post->title }}
                                    </h1>
                                    <p>{{ $post->sub_title }}</p>
                                    <div class="mt-2">
                                        @foreach ($post->categories as $category)
                                        <a href="{{ route('filamentblog.category.post', ['category' => $category->slug]) }}">
                                            <span class="inline-flex px-2 py-1 mr-2 text-xs font-semibold rounded-full bg-primary-200 text-primary-800">{{ $category->name }}
                                            </span>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex items-center justify-between py-5 mb-5 gap-x-3">
                                    <div>
                                        <div class="flex items-center gap-4">
                                            @if($post->user)
                                                <img class="h-14 w-14 overflow-hidden rounded-full border-4 border-white bg-zinc-300 object-cover text-[0] ring-1 ring-slate-300" src="{{ $post->user->avatar ?? asset('path/to/default-avatar.png') }}" alt="{{ $post->user->name() }}">
                                                <div>
                                                    <span title="{{ $post->user->name() }}" class="block max-w-[150px] overflow-hidden text-ellipsis whitespace-nowrap font-semibold">{{ $post->user->name() }}</span>
                                                    <span class="block text-sm font-medium font-semibold whitespace-nowrap text-zinc-600">
                                                        {{ $post->formattedPublishedDate() }}
                                                    </span>
                                                </div>
                                            @else
                                                <div>
                                                    <span class="block max-w-[150px] overflow-hidden text-ellipsis whitespace-nowrap font-semibold">Unknown Author</span>
                                                    <span class="block text-sm font-medium font-semibold whitespace-nowrap text-zinc-600">
                                                        {{ $post->formattedPublishedDate() }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <article class="m-auto leading-6">
                                        {!! tiptap_converter()->asHTML($post->body, toc: true, maxDepth: 3) !!}
                                    </article>

                                    @if($post->tags->count())
                                    <div class="pt-10">
                                        <span class="block mb-3 font-semibold">Tags</span>
                                        <div class="space-x-2 space-y-1">
                                            @foreach ($post->tags as $tag)
                                            <a href="{{ route('filamentblog.tag.post', ['tag' => $tag->slug]) }}" class="px-3 py-1 text-sm font-medium text-black border rounded-full border-slate-300 text-slate-600 hover:bg-slate-100">
                                                {{ $tag->name }}
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($post->comments->count())
                        <div id="comments" class="py-10 border-t-2">
                            <div class="mb-4">
                                <h3 class="mb-2 text-2xl font-semibold">Comments</h3>
                            </div>
                            <div class="flex flex-col divide-y gap-y-6">
                                @foreach($post->comments as $comment)
                                <article class="pt-4 text-base">
                                    <div class="flex items-center gap-4 mb-4">
                                        @if($comment->user)
                                            <img class="h-14 w-14 overflow-hidden rounded-full border-4 border-white bg-zinc-300 object-cover text-[0] ring-1 ring-slate-300" src="{{ $comment->user->avatar ?? asset('path/to/default-avatar.png') }}" alt="avatar">
                                            <div>
                                                <span class="block max-w-[150px] overflow-hidden text-ellipsis whitespace-nowrap font-semibold">
                                                    {{ $comment->user->{config('filamentblog.user.columns.name')} }}
                                                </span>
                                                <span class="block text-sm font-medium whitespace-nowrap text-zinc-600">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        @else
                                            <div>
                                                <span class="block max-w-[150px] overflow-hidden text-ellipsis whitespace-nowrap font-semibold">
                                                    Anonymous
                                                </span>
                                                <span class="block text-sm font-medium whitespace-nowrap text-zinc-600">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-gray-500">
                                        {{ $comment->comment }}
                                    </p>
                                </article>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        <x-blog-comment :post="$post" />
                    </div>
                    <div>
                        {{-- Ads Section            --}}
                        {{-- <div--}}
                        {{-- class="sticky top-24 flex h-[600px] w-[160px] items-center justify-center overflow-hidden rounded bg-slate-200 font-medium text-slate-500/20">--}}
                        {{-- <span>ADS</span>--}}
                        {{-- </div>--}}
                    </div>
                </div>
            </div>

            <div>
                <div>
                    <div class="relative flex items-center mb-6 gap-x-8">
                        <h2 class="text-xl font-semibold whitespace-nowrap">
                            <span class="font-bold text-primary">#</span> Related Posts
                        </h2>
                        <div class="flex items-center w-full">
                            <span class="h-0.5 w-full rounded-full bg-slate-200"></span>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-3 sm:grid-cols-1 gap-x-12 gap-y-10">
                        @forelse($post->relatedPosts() as $relatedPost)
                        <x-blog-card :post="$relatedPost" />
                        @empty
                        <div class="col-span-3">
                            <p class="text-xl font-semibold text-center text-gray-300">No related posts found.</p>
                        </div>
                        @endforelse
                    </div>
                    <div class="flex justify-center pt-20">
                        <a href="{{ route('filamentblog.post.all') }}" class="flex items-center justify-center px-20 py-4 text-sm font-semibold transition-all duration-300 rounded-full md:gap-x-5 bg-slate-100 hover:bg-slate-200">
                            <span>Show all blogs</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6m0 0H9m9 0v9" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {!! $shareButton?->script_code !!}
</x-blog-layout>