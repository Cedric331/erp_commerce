<x-filament-panels::page>
    <div class="py-10 container mx-auto px-5">
        <!-- component -->
        <div class="relative">
            <div class="border-r-4 border-black absolute h-full top-0" style="margin-left: 9px"></div>
            <ul class="list-none m-0 p-0">
                @foreach ($activities as $activity)
                <li class="mb-5">
                    <div class="flex group items-center">
                        <div class="bg-gray-800 group-hover:bg-[#137863] z-10 rounded-full border-4 border-black  h-5 w-5">
                            <div class="bg-black h-1 w-6 items-center  ml-4 mt-1"></div>
                        </div>
                        <div class="flex-1 ml-4 z-10 font-medium">
                            <div class="order-1 space-y-2 bg-gray-800 rounded-lg  shadow-2xl transition-ease lg:w-8/12 px-6 py-4 hover:border-[#137863] border-2">
                                <h3 class="mb-3 font-bold text-white text-2xl">{{ $activity->event }}</h3>
                                <p class="text-sm pb-4 font-medium leading-snug tracking-wide text-gray-300 text-opacity-100">
                                    {{ $activity->description }}
                                </p>
                                <hr />
                                <p class="text-sm text-gray-100">Fait le {{ date('d/m/Y', strtotime($activity->created_at)) }} {{ $activity->causer ? 'par ' . $activity->causer->name : ' - Automatique' }}</p>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-filament-panels::page>
