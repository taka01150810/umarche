<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            商品の詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="md:flex md:justify-around">
                        <div class="md:w-1/2">
                            <x-thumbnail filename="{{ $product->imageFirst->filename ?? '' }}" type="products"/>
                        </div>
                        <div class="md:w-1/2 ml-4">
                            <h2 class="tracking-widest text-xs title-font font-medium text-gray-400 mb-4">{{ $product->category->name }}</h2>
                            <h1 class="title-font text-lg font-medium text-gray-900 mb-4">{{ $product->name }}</h1>
                            <p class="leading-relaxed mb-3">{{ $product->information }}</p>
                            <div class="flex justify-around items-center">
                                <div>
                                    <p class="mt-1 title-font font-medium text-2xl text-gray-900">{{ number_format($product->price) }}<span class="text-sm text-gray-700">円(税込)</span></p>
                                </div>
                                <div class="flex ml-auto">
                                    <div><span class="mr-3">数量</span>
                                    </div>
                                    <div class="relative">
                                      <select class="rounded border appearance-none border-gray-300 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-base pl-3 pr-10">
                                        <option>SM</option>
                                        <option>M</option>
                                        <option>L</option>
                                        <option>XL</option>
                                      </select>
                                      <span class="absolute right-0 top-0 h-full w-10 text-center text-gray-600 pointer-events-none flex items-center justify-center">
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4" viewBox="0 0 24 24" data-darkreader-inline-stroke="" style="--darkreader-inline-stroke:currentColor;">
                                          <path d="M6 9l6 6 6-6"></path>
                                        </svg>
                                      </span>
                                    </div>
                                </div>
                                <button class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">カートに入れる</button>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-400 my-8"></div>
                        <div class="mb-4 text-center">この商品を販売しているショップ</div>
                        <div class="mb-4 text-center">{{ $product->shop->name }}</div>
                        <div class="mb-4 text-center">
                            @if($product->shop->filename !== null)
                                <img src="{{ asset('storage/shops/' . $product->shop->filename )}}" class="w-40 h-40 rounded-full mx-auto object-cover">
                            @else
                                <img src="">
                            @endif
                        </div>      
                        <div class="mb-4 text-center">
                            <button data-micromodal-trigger="modal-1" href='javascript:;' type=“button” class="text-white bg-gray-500 border-0 py-2 px-6 focus:outline-none hover:bg-gray-600 rounded">
                                ショップの詳細を見る
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
          <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
              <h2 class="text-xl text-gray-700" id="modal-1-title">
                 {{ $product->shop->name }}
              </h2>
              <button type="button" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            </header>
            <main class="modal__content" id="modal-1-content">
              <p>
                {{ $product->shop->information }}
              </p>
            </main>
            <footer class="modal__footer">
              <button type="button" class="modal__btn" data-micromodal-close aria-label="閉じる">閉じる</button>
            </footer>
          </div>
        </div>
    </div>
</x-app-layout>
