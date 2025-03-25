<div>
    {{-- Stop trying to control. --}}
    @if($type='login')
        <div class="p-4 max-h-lvh flex" >
            <div class="mx-auto card lg:w-1/3 shadow bg-white mt-[20vh]">
                <div class="card-body w-full">
                    <h2 class="text-primary text-xl text-center font-medium"> Login </h2>
                    <div class="divider"></div>
                    <form class="w-full" wire:submit="authhenticate">
                        @csrf
                        <fieldset class="fieldset my-3">
                            <legend class="fieldset-legend">What is your name?</legend>
                            <input type="email" class="input input-md w-full @error('username') input-warning @enderror" placeholder="Type here" wire:model="username" />
                            @error('username')<p class="fieldset-label">Optional</p> @enderror
                        </fieldset>

                        <fieldset class="fieldset my-3">
                            <legend class="fieldset-legend">Password?</legend>
                            <input type="password" placeholder="Password" class="input input-md w-full @error('password') input-warning @enderror" wire:model="password" />
                            @error('')<p class="fieldset-label">Optional</p> @enderror
                        </fieldset>
                        <button class="btn btn-success my-2" type="submit">Submit form</button>
                    </form>
                </div>
            </div>
        </div>
    @else

    @endif

</div>
