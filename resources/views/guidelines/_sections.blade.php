@foreach($rows as $sec)
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">{{ $sec['heading'] ?? '' }}</h3>
                        @if(!empty($sec['body']))
                        <p>{{ $sec['body'] }}</p>
                        @endif
                        @if(!empty($sec['items']))
                        <ul class="space-y-2 list-disc list-inside">
                            @foreach($sec['items'] as $li)
                            <li>{{ $li }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>

@endforeach
