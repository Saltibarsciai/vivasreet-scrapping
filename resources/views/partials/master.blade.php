@extends('layout')
@section('content')
    <main class="w-75 mx-auto">
        <img src="https://uglymugs.org/um/uploads/Vivastreet-logo-grey_picmonkeyed.jpg" class="w-50 mt-2" alt="vivastreet">
        <form class="form-inline w-100 p-2 mt-5" method="post" action="{{route('cars.store')}}">
            @csrf
            <div style="width: 70%; margin-right: 5%">
                <label class="d-none" for="inputPassword"></label>
                <input name="link" type="text" class="form-control w-100" id="inputPassword" value="https://search.vivastreet.co.uk/cars/gb" placeholder="https://search.vivastreet.co.uk/cars/gb"/>
            </div>
            <button type="submit" class="btn btn-primary w-25">Download content</button>
        </form>
        <h1 class="my-5 font-weight-bold">LIST</h1>
        <ul class="list-group">
            @foreach($data as $item)
                <li class="list-group-item d-flex p-4">
                    <aside class="mr-3 images-grid">
                        @foreach($item->images as $i => $image)
                            <img class="mr-1" width="150px" height="150px" src="{{$image->path}}" alt="{{$image->id}}"/>
                        @endforeach
                    </aside>
                    <article class="container">
                        <header class="font-weight-bolder">
                            <h3>{{$item->title}}</h3>
                        </header>
                        <p>
                            {{$item->description}}
                        </p>
                        <p>
                            Ad ID: {{$item->ad_id}} | Price: {{$item->price}} | Year: {{$item->year}} | Mileage: {{$item->mileage}}  {{$item->phone === 0 ?:"| Phone: $item->phone"}}
                        </p>
                        <a href="{{$item->link_to_website}}" class="btn btn-primary">
                            Go to website
                        </a>
                    </article>
                </li>
            @endforeach
        </ul>
    </main>
@stop
