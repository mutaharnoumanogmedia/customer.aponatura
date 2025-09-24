                        @php
                            $currentLocale = session('locale', app()->getLocale());
                            $languages = [
                                'en' => ['label' => 'English', 'flag' => 'https://flagcdn.com/w20/gb.png'],
                                'de' => ['label' => 'Deutsch', 'flag' => 'https://flagcdn.com/w20/de.png'],
                            ];
                        @endphp

                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center"
                                type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ $languages[$currentLocale]['flag'] }}" alt="flag" class="me-2"
                                    width="20">
                                {{ $languages[$currentLocale]['label'] }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                                @foreach ($languages as $code => $lang)
                                    @if ($code !== $currentLocale)
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="{{ url('/lang/' . $code) }}">
                                                <img src="{{ $lang['flag'] }}" alt="{{ $lang['label'] }}" class="me-2"
                                                    width="20">
                                                {{ $lang['label'] }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
