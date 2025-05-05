@extends('backend.layouts.main')

@section('title', 'Dashboard')

@section('contents')
{{--    @svg('heroicon-o-arrow-left', 'w-6 h-6', ['style' => 'color: #555'])--}}
    <!-- statistixs -->
    <div class="w-full bg-white">
        <div class="stats stats-vertical lg:stats-horizontal shadow w-full">
            <div class="stat">
                <div class="stat-figure text-secondary">
                    @svg('heroicon-o-information-circle', 'w-8 h-8')
                </div>
                <div class="stat-title">Videos</div>
                <div class="stat-value">{{ \App\Models\General\MeetingVideo::count() }}</div>
                <div class="stat-desc">Jan 1st - Feb 1st</div>
            </div>

            <div class="stat">
                <div class="stat-figure text-secondary">
                    @svg('heroicon-o-map-pin', 'w-8 h-8')
                </div>
                <div class="stat-title">Locations</div>
                <div class="stat-value">{{ \App\Models\General\Location::count() }}</div>
                <div class="stat-desc">↗︎ 400 (22%)</div>
            </div>

            <div class="stat">
                <div class="stat-figure text-secondary">
                    @svg('heroicon-o-magnifying-glass', 'w-8 h-8')
                </div>
                <div class="stat-title">Meetings</div>
                <div class="stat-value">{{ \App\Models\General\Meeting::count() }}</div>
                <div class="stat-desc">↘︎ 90 (14%)</div>
            </div>

            <div class="stat">
                <div class="stat-figure text-secondary">
                    @svg('heroicon-o-archive-box-arrow-down', 'w-8 h-8')
                </div>
                <div class="stat-title">Meetings</div>
                <div class="stat-value">{{ \App\Models\General\Application::count() }}</div>
                <div class="stat-desc">↘︎ 90 (14%)</div>
            </div>
        </div>
    </div>

    <!-- recent search-->
    <div class="flex">
        <div class="card bg-base-100 w-full lg:w-1/2 shadow-sm mt-5">
            <div class="card-body">
                <h2 class="card-title">Recent Searches</h2>
                <div class="divider"></div>
                <div class="overflow-x-auto rounded-box  bg-base-100">
                    <table class="table">
                        <!-- head -->
                        <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Job</th>
                            <th>Favorite Color</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- row 1 -->
                        <tr>
                            <th>1</th>
                            <td>Cy Ganderton</td>
                            <td>Quality Control Specialist</td>
                            <td>Blue</td>
                        </tr>
                        <!-- row 2 -->
                        <tr>
                            <th>2</th>
                            <td>Hart Hagerty</td>
                            <td>Desktop Support Technician</td>
                            <td>Purple</td>
                        </tr>
                        <!-- row 3 -->
                        <tr>
                            <th>3</th>
                            <td>Brice Swyre</td>
                            <td>Tax Accountant</td>
                            <td>Red</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
