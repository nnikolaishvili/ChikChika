@extends('layouts.app')

@section('content')
    <div class="bg-neutral-900">
        <div class="flex">

            {{-- nagivation --}}
            <x-menu :authUser="$authUser"></x-menu>

            <div class="w-full p-5 border border-gray-600 h-auto border-t-0 border-b-0">
                <form action="{{ route('settings', $authUser->username) }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="max-w-md mb-5">
                        <h1 class="text-lg mb-5">Profile information</h1>
                        <div class="flex items-center">
                            <div class="text-md my-5">Name</div>
                            <div class="w-full ml-4">
                                <input type="text" placeholder="Type name" name="name"
                                       class="w-full input input-bordered max-w-sm mb-2" value="{{ $authUser->name }}"/>
                                @error('name')
                                <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="text-md my-5 mr-5">Username</div>
                            <div class="w-full">
                                <input type="text" placeholder="Type username" name="username"
                                       class="input w-full input-bordered max-w-sm mb-2"
                                       value="{{ $authUser->username }}"/>
                                @error('username')
                                <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="text-md my-5 mr-5">Make Account Private</div>
                            <div>
                                <input type="checkbox" @if($authUser->is_private) checked="checked"
                                       @endif name="is_private" value="1" class="checkbox checkbox-md"/>
                                @error('is_private')
                                <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="avatar mr-5">
                                <div class="w-24 rounded-full">
                                    <img class="show-image" src="{{ $authUser->ImageUrl() }}" alt="profile-image"/>
                                </div>
                            </div>
                            <div>
                                <input type="file" name="image" class="btn btn-outline btn-sm w-full"
                                       style="padding-top: 0.3rem"/>
                                @error('image')
                                <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <button type="submit" class="btn btn-info btn-wide">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $("input[name=image]").change(function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = $('.show-image').attr('src', e.target.result);
                    $('.show-image').html(img);
                };

                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
@endpush
