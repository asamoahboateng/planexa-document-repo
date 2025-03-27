<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="text-2xl font-semibold">
                Commands
                <button class="btn btn-sm btn-outline btn-success float-end" wire:click="resetCommands"> Reset Table</button>
            </h3>
        </div>


        @if($output)
            <div class="px-1 my-2">
                <div role="alert" class="py-5 alert bg-white alert-vertical md:alert-horizontal shadow">
                    @svg('heroicon-o-information-circle', 'w-6 h-6')
                    <div>
                        <h3>Command Output:</h3>
                        <pre>{{ $output }}</pre>
                    </div>
                </div>
            </div>
        @endif


        <div class="card-body bg-base-100 ">
            <div class="overflow-x-auto" wire:loading.remove="callcommand">
                <table class="table">
                    <!-- head -->
                    <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Last Run</th>
                        <th>Run</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- row 1 -->
                    @foreach($commands as $command)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>{{ $command->name }}</td>
                            <td>{{ $command->updated_at->format('d l, Y H:m:s') }}</td>
                            <td>
                                @if($command->status == 0)
                                    <button class="btn btn-sm btn-outline btn-warning" wire:click="callcommand('{{ $command->id  }}')" wire:loading.remove>Run Command</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="mx-auto" wire:loading wire:target="callcommand">
                <span class="loading loading-bars loading-xs"></span>
                <span class="loading loading-bars loading-sm"></span>
                <span class="loading loading-bars loading-md"></span>
                <span class="loading loading-bars loading-lg"></span>
                <span class="loading loading-bars loading-xl"></span>
            </div>
        </div>
    </div>
</div>
