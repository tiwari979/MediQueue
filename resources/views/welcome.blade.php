<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MediQueue - Hospital Workflow Platform</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #101828;
            --muted: #667085;
            --line: #d9e2e7;
            --green: #0ca678;
            --green-dark: #087f5b;
            --blue: #228be6;
            --aqua: #e8f8f5;
            --peach: #fde9e2;
            --lavender: #f1edff;
            --cream: #fff9e8;
        }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
            background: #ffffff;
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: linear-gradient(145deg, var(--green), #31c48d);
            position: relative;
            box-shadow: 0 10px 20px rgba(12, 166, 120, 0.2);
        }

        .brand-mark::before,
        .brand-mark::after {
            content: "";
            position: absolute;
            background: #ffffff;
        }

        .brand-mark::before {
            width: 8px;
            height: 22px;
            left: 13px;
            top: 6px;
            border-radius: 5px;
        }

        .brand-mark::after {
            width: 22px;
            height: 8px;
            left: 6px;
            top: 13px;
            border-radius: 5px;
        }

        .hero-scene {
            background:
                linear-gradient(90deg, rgba(255, 255, 255, 0.96) 0%, rgba(255, 255, 255, 0.82) 38%, rgba(255, 255, 255, 0.42) 100%),
                radial-gradient(circle at 78% 22%, rgba(34, 139, 230, 0.2), transparent 28%),
                radial-gradient(circle at 92% 70%, rgba(12, 166, 120, 0.22), transparent 30%),
                linear-gradient(135deg, #eaf6ff 0%, #f3fbf8 42%, #fff5ec 100%);
        }

        .doctor-visual {
            min-height: 330px;
            background:
                radial-gradient(circle at 26% 28%, #cfe8ff 0 7%, transparent 7.5%),
                radial-gradient(circle at 77% 24%, #d7f2ea 0 8%, transparent 8.5%),
                linear-gradient(165deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.58));
        }

        .person {
            position: absolute;
            border-radius: 999px 999px 18px 18px;
            opacity: 0.82;
        }

        .person::before {
            content: "";
            position: absolute;
            width: 54px;
            height: 54px;
            border-radius: 50%;
            left: calc(50% - 27px);
            top: -42px;
            background: #ffd6c2;
            border: 4px solid rgba(255, 255, 255, 0.8);
        }

        .soft-shadow {
            box-shadow: 0 18px 45px rgba(16, 24, 40, 0.12);
        }
    </style>
</head>
<body class="antialiased selection:bg-emerald-600 selection:text-white">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-5 px-4 py-3 sm:px-6 lg:px-8">
            <a href="/" class="flex items-center gap-3" aria-label="MediQueue home">
                <span class="brand-mark"></span>
                <span>
                    <span class="block text-2xl font-extrabold tracking-tight text-emerald-700">MediQueue</span>
                    <span class="block text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500">Hospital System</span>
                </span>
            </a>

            <nav class="hidden items-center gap-7 text-sm font-semibold text-slate-700 lg:flex">
                <a href="#modules" class="hover:text-emerald-700">Modules</a>
                <a href="#flow" class="hover:text-emerald-700">Workflow</a>
                <a href="#reports" class="hover:text-emerald-700">Reports</a>
                <a href="#faq" class="hover:text-emerald-700">Help</a>
            </nav>

            <div class="flex items-center gap-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-full bg-emerald-700 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-full bg-emerald-700 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800">Staff Login</a>
                    @endauth
                @endif
            </div>
        </div>

        <div class="border-t border-slate-100 bg-slate-50">
            <div class="mx-auto flex max-w-7xl items-center gap-3 overflow-x-auto px-4 py-2.5 text-sm font-semibold text-slate-700 sm:px-6 lg:justify-center lg:px-8">
                <a href="#callback" class="flex shrink-0 items-center gap-2 rounded-full px-4 py-2 hover:bg-white">
                    <svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.2-1.2a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2Z"/></svg>
                    Request Callback
                </a>
                <a href="#book" class="flex shrink-0 items-center gap-2 rounded-full bg-[var(--peach)] px-5 py-2 text-slate-900">
                    <svg class="h-5 w-5 text-slate-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/></svg>
                    Book Appointment
                </a>
                <a href="#checkups" class="flex shrink-0 items-center gap-2 rounded-full px-4 py-2 hover:bg-white">
                    <svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6v5a8 8 0 0 1-16 0V6M8 3v4M16 3v4M12 19v3M8 22h8"/></svg>
                    Get Health Checkup
                </a>
            </div>
        </div>
    </header>

    <main>
        <section class="hero-scene border-b border-slate-200">
            <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8 lg:py-16">
                <div>
                    <p class="mb-4 inline-flex rounded-full border border-emerald-200 bg-white/75 px-4 py-2 text-sm font-bold text-emerald-800">One front desk for OPD, beds, patients, pharmacy, and reports</p>
                    <h1 class="max-w-3xl text-4xl font-extrabold leading-tight tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                        Healthcare operations made clear from the first screen.
                    </h1>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-700">
                        MediQueue helps hospital staff register patients, create OPD tokens, monitor bed occupancy, dispense medicines, and review daily performance without jumping between disconnected tools.
                    </p>

                    <div id="book" class="mt-8 max-w-3xl rounded-lg border border-slate-200 bg-white p-2 soft-shadow">
                        <form class="flex flex-col gap-2 sm:flex-row">
                            <label class="sr-only" for="landing-search">Search module</label>
                            <input id="landing-search" type="search" placeholder="Search patients, OPD tokens, beds, inventory or reports" class="min-h-12 flex-1 rounded-md border-0 px-4 text-base text-slate-900 outline-none placeholder:text-slate-500 focus:ring-2 focus:ring-emerald-600">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex min-h-12 items-center justify-center rounded-md bg-slate-950 px-6 text-sm font-bold text-white transition hover:bg-slate-800">Open Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex min-h-12 items-center justify-center rounded-md bg-slate-950 px-6 text-sm font-bold text-white transition hover:bg-slate-800">Login</a>
                                @endauth
                            @endif
                        </form>
                    </div>
                </div>

                <div class="doctor-visual relative hidden overflow-hidden rounded-lg border border-white/70 p-6 soft-shadow lg:block">
                    <div class="person left-10 top-28 h-48 w-28 bg-sky-300"></div>
                    <div class="person right-16 top-24 h-56 w-32 bg-emerald-300"></div>
                    <div class="absolute bottom-8 left-8 right-8 rounded-lg border border-slate-200 bg-white/92 p-4 shadow-xl">
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Live desk</p>
                                <p class="text-lg font-extrabold text-slate-950">Today at a glance</p>
                            </div>
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">Active</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="rounded-md bg-emerald-50 p-3">
                                <p class="text-2xl font-extrabold text-emerald-800">42</p>
                                <p class="text-xs font-semibold text-slate-600">OPD tokens</p>
                            </div>
                            <div class="rounded-md bg-sky-50 p-3">
                                <p class="text-2xl font-extrabold text-sky-800">18</p>
                                <p class="text-xs font-semibold text-slate-600">Beds free</p>
                            </div>
                            <div class="rounded-md bg-orange-50 p-3">
                                <p class="text-2xl font-extrabold text-orange-700">7</p>
                                <p class="text-xs font-semibold text-slate-600">Low stock</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="modules" class="bg-white py-12">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1fr_0.9fr] lg:px-8">
                <div class="grid gap-4 sm:grid-cols-2">
                    <a href="{{ Route::has('login') ? route('login') : '#' }}" class="rounded-lg border border-lime-200 bg-[var(--cream)] p-5 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="mb-8 flex items-center justify-between">
                            <h2 class="text-2xl font-extrabold leading-7 text-slate-950">Create OPD Token</h2>
                            <span class="rounded-full bg-lime-100 p-3 text-lime-700">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11h6M9 15h6M8 3v3M16 3v3M5 5h14v16H5z"/></svg>
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-700">Generate queue tokens and prioritize emergency or senior patients.</p>
                    </a>
                    <a href="{{ Route::has('login') ? route('login') : '#' }}" class="rounded-lg border border-sky-200 bg-sky-50 p-5 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="mb-8 flex items-center justify-between">
                            <h2 class="text-2xl font-extrabold text-slate-950">Beds</h2>
                            <span class="rounded-full bg-sky-100 p-3 text-sky-700">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7v12M21 12v7M3 14h18M7 10h4a2 2 0 0 1 2 2v2H5v-2a2 2 0 0 1 2-2ZM17 10h1a3 3 0 0 1 3 3v1h-8v-2a2 2 0 0 1 2-2h2Z"/></svg>
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-700">See occupied, available, admitted, and discharge-ready beds.</p>
                    </a>
                    <a href="{{ Route::has('login') ? route('login') : '#' }}" class="rounded-lg border border-violet-200 bg-[var(--lavender)] p-5 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="mb-8 flex items-center justify-between">
                            <h2 class="text-2xl font-extrabold text-slate-950">Patients</h2>
                            <span class="rounded-full bg-violet-100 p-3 text-violet-700">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21a8 8 0 0 0-16 0M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg>
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-700">Maintain patient details, visits, admissions, and discharge status.</p>
                    </a>
                    <a href="{{ Route::has('login') ? route('login') : '#' }}" class="rounded-lg border border-rose-200 bg-rose-50 p-5 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="mb-8 flex items-center justify-between">
                            <h2 class="text-2xl font-extrabold text-slate-950">Pharmacy</h2>
                            <span class="rounded-full bg-rose-100 p-3 text-rose-700">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m10 21 10-10a5 5 0 0 0-7-7L3 14a5 5 0 0 0 7 7ZM8 9l7 7"/></svg>
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-700">Track stock, dispense medicine, and catch expiry or low inventory.</p>
                    </a>
                </div>

                <div id="checkups" class="flex flex-col justify-center">
                    <p class="text-sm font-extrabold uppercase tracking-[0.2em] text-emerald-700">Fast actions</p>
                    <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">Staff can move straight to the task that matters.</h2>
                    <div class="mt-7 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-lg border border-emerald-200 bg-white p-6 text-center shadow-sm">
                            <svg class="mx-auto mb-4 h-14 w-14 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.5 0 3 1.2 3 3s-1.5 3-3 3H5c-1.5 0-3-1.2-3-3s1.5-3 3-3M12 4v10M7 9l5-5 5 5"/></svg>
                            <h3 class="text-lg font-extrabold">Daily Reports</h3>
                        </div>
                        <div class="rounded-lg border border-emerald-200 bg-white p-6 text-center shadow-sm">
                            <svg class="mx-auto mb-4 h-14 w-14 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6M12 9v6M7 3h10l3 5v13H4V8zM7 3v5h13"/></svg>
                            <h3 class="text-lg font-extrabold">Inventory Logs</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="flow" class="border-y border-emerald-100 bg-emerald-50/60 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-10 max-w-3xl">
                    <p class="text-sm font-extrabold uppercase tracking-[0.2em] text-emerald-700">Hospital flow</p>
                    <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">From registration to reporting, the handoff stays visible.</h2>
                </div>
                <div class="grid gap-5 md:grid-cols-4">
                    @foreach ([
                        ['01', 'Register patient', 'Capture patient details once and keep the record reusable.'],
                        ['02', 'Route OPD visit', 'Create tokens, call patients, and finish visits department-wise.'],
                        ['03', 'Manage admission', 'Allocate beds, update ward occupancy, and release on discharge.'],
                        ['04', 'Review outcomes', 'Check OPD, bed, and inventory reports for the day.'],
                    ] as $step)
                        <div class="rounded-lg border border-white bg-white p-6 shadow-sm">
                            <p class="text-sm font-extrabold text-emerald-700">{{ $step[0] }}</p>
                            <h3 class="mt-4 text-xl font-extrabold text-slate-950">{{ $step[1] }}</h3>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $step[2] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="reports" class="bg-white py-16">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.82fr_1.18fr] lg:px-8">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">Reports that tell staff what happened today.</h2>
                    <p class="mt-4 text-lg leading-8 text-slate-700">The landing page now previews the operational story: queue pressure, bed availability, pharmacy risk, and the reports that make hospital activity easier to audit.</p>
                    <a href="{{ Route::has('login') ? route('login') : '#' }}" class="mt-7 inline-flex rounded-md border border-rose-300 px-6 py-3 text-sm font-extrabold text-rose-700 transition hover:bg-rose-50">View reports</a>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <article class="rounded-lg border border-slate-200 bg-white p-6 soft-shadow">
                        <p class="mb-5 text-sm font-bold text-slate-500">OPD report</p>
                        <h3 class="text-2xl font-extrabold leading-8 text-slate-950">Department queues, completed visits, and waiting load in one summary.</h3>
                        <div class="mt-8 space-y-3">
                            <div class="h-3 w-full rounded-full bg-slate-100"><div class="h-3 w-4/5 rounded-full bg-emerald-600"></div></div>
                            <div class="h-3 w-full rounded-full bg-slate-100"><div class="h-3 w-3/5 rounded-full bg-blue-600"></div></div>
                            <div class="h-3 w-full rounded-full bg-slate-100"><div class="h-3 w-2/5 rounded-full bg-orange-500"></div></div>
                        </div>
                    </article>
                    <article class="rounded-lg border border-slate-200 bg-white p-6 soft-shadow">
                        <p class="mb-5 text-sm font-bold text-slate-500">Patient story</p>
                        <h3 class="text-2xl font-extrabold leading-8 text-slate-950">A patient moves from registration to OPD to admission without duplicate entries.</h3>
                        <p class="mt-8 text-sm leading-6 text-slate-600">Reception, ward staff, and pharmacy use the same record, so each department sees the latest status.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="faq" class="border-t border-slate-200 bg-slate-50 py-16">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.85fr_1.15fr] lg:px-8">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-950">Common questions</h2>
                    <p class="mt-4 text-slate-700">A compact overview for visitors who want to understand what MediQueue actually does before logging in.</p>
                </div>
                <div class="space-y-3">
                    @foreach ([
                        ['Who uses MediQueue?', 'Receptionists, doctors, ward staff, pharmacy teams, and administrators can use it for daily hospital coordination.'],
                        ['What does it manage?', 'Patient records, OPD queues, admissions, bed status, inventory, dispensing logs, and summary reports.'],
                        ['Is this only a public website?', 'No. The landing page introduces the system, while authenticated staff work inside the operational dashboard.'],
                    ] as $item)
                        <details class="group rounded-lg border border-slate-200 bg-white p-5">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-base font-extrabold text-slate-950">
                                {{ $item[0] }}
                                <span class="text-2xl leading-none text-emerald-700 group-open:rotate-45">+</span>
                            </summary>
                            <p class="mt-4 text-sm leading-6 text-slate-600">{{ $item[1] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <footer id="callback" class="bg-emerald-50 py-12">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 md:grid-cols-4 lg:px-8">
            <div>
                <div class="flex items-center gap-3">
                    <span class="brand-mark"></span>
                    <span class="text-2xl font-extrabold text-emerald-700">MediQueue</span>
                </div>
                <p class="mt-4 text-sm leading-6 text-slate-600">Hospital workflow platform for queue, bed, patient, inventory, and reporting operations.</p>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-950">For Staff</h3>
                <ul class="mt-4 space-y-3 text-sm text-slate-700">
                    <li>OPD Tokens</li>
                    <li>Patient Records</li>
                    <li>Admissions</li>
                </ul>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-950">Operations</h3>
                <ul class="mt-4 space-y-3 text-sm text-slate-700">
                    <li>Bed Management</li>
                    <li>Pharmacy Inventory</li>
                    <li>Daily Reports</li>
                </ul>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-950">Access</h3>
                <p class="mt-4 text-sm leading-6 text-slate-700">Sign in with a staff account to open the protected hospital dashboard.</p>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="mt-5 inline-flex rounded-md bg-emerald-700 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-emerald-800">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="mt-5 inline-flex rounded-md bg-emerald-700 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-emerald-800">Staff Login</a>
                    @endauth
                @endif
            </div>
        </div>
        <div class="mx-auto mt-10 max-w-7xl border-t border-emerald-100 px-4 pt-6 text-sm text-slate-600 sm:px-6 lg:px-8">
            &copy; {{ date('Y') }} MediQueue Hospital Management System. All rights reserved.
        </div>
    </footer>
</body>
</html>
