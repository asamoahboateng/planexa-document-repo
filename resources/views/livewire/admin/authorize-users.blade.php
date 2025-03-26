<div>
    {{-- Stop trying to control. --}}
    @if($type='login')
        <div class="p-4 max-h-lvh flex" >
            <div class="mx-auto card lg:w-1/3 shadow bg-white mt-[20vh]">
                <div class="card-body w-full">
                    <h2 class="text-primary text-xl text-center font-medium"> Login </h2>
                    <div class="divider"></div>
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div role="alert" class="alert alert-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Error! {{ $error }}</span>
                            </div>
                        @endforeach
                    @endif
                    <form class="w-full" wire:submit="authhenticate">
                        @csrf
                        <fieldset class="fieldset my-3">
                            <legend class="fieldset-legend">What is your name?</legend>
                            <input type="email" class="input input-md w-full @error('username') input-warning @enderror" placeholder="Type here" wire:model="username" />
                            @error('username')<p class="fieldset-label text-danger-600">{{ $message }}</p> @enderror
                        </fieldset>

                        <fieldset class="fieldset my-3">
                            <legend class="fieldset-legend">Password?</legend>
                            <input type="password" placeholder="Password" class="input input-md w-full @error('password') input-warning @enderror" wire:model="password" />
                            @error('password')<p class="fieldset-label text-danger-600">{{ $message }}</p> @enderror
                        </fieldset>
                        <button class="btn btn-success my-2" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    @else

    @endif

</div>
