<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Changelog - APPIKS.ID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<body>
    <div class="container my-5">
        <h1 class="fw-bold mb-2">Changelog</h1>
        <a class="mb-4" href="/docs">API Documentation</a>
        <div class="accordion" id="accordionExample">
            @foreach ($grouped as $index => $commit)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $index }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                            aria-controls="collapse{{ $index }}">
                            <div>

                                <span
                                    class="badge
                            @if ($commit['keyword'] === 'FEAT') bg-success
                            @elseif($commit['keyword'] === 'FIX') bg-danger
                            @elseif($commit['keyword'] === 'DOCS') bg-info
                            @else bg-secondary @endif
                            me-2">
                                    {{ $commit['keyword'] }}
                                </span>

                                <a href="{{ $commit['url'] }}"
                                    class="fw-semibold text-dark text-decoration-none me-auto" target="_blank">
                                    {{ $commit['desc'] }}
                                </a>
                            </div>
                            <div>


                                <small class="text-muted ms-3">
                                    {{ date('d M Y', strtotime($commit['date'])) }} — {{ $commit['author'] }}
                                </small>
                            </div>
                        </button>
                    </h2>

                    <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                        aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            @if ($commit['files']->count())
                                <ul class="list-unstyled mb-0">
                                    @foreach ($commit['files'] as $file)
                                        <li class="d-flex align-items-center mb-1">
                                            <span
                                                class="fw-bold me-2
                                        @if ($file['status'] === 'added') text-success
                                        @elseif($file['status'] === 'modified') text-primary
                                        @elseif($file['status'] === 'removed') text-danger @endif">
                                                {{ strtoupper($file['status']) }}
                                            </span>
                                            <a href="{{ $file['url'] }}" class="text-decoration-none text-dark"
                                                target="_blank">
                                                {{ $file['name'] }}
                                            </a>
                                            <small class="text-muted ms-2">
                                                (+{{ $file['additions'] }} / -{{ $file['deletions'] }})
                                            </small>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <em class="text-muted">No file changes listed</em>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
</body>

</html>
