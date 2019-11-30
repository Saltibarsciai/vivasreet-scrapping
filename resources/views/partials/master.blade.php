@extends('layout')

@section('content')
    <main class="w-75 mx-auto">
        <form class="form-inline w-100 p-2 mt-2">
            <div style="width: 70%; margin-right: 5%">
                <label class="d-none" for="inputPassword"></label>
                <input type="password" class="form-control w-100" id="inputPassword" placeholder="Password"/>
            </div>
            <button type="submit" class="btn btn-primary w-25">Download</button>
        </form>
        <h1 class="my-5 font-weight-bold">LIST</h1>
        <ul class="list-group">
            <li class="list-group-item d-flex">
                <aside>
                    <div class="d-flex mt-1">
                        <img class="mr-1" src="https://colorlib.com/unite/wp-content/uploads/sites/7/2013/03/image-alignment-150x150.jpg" alt="150">
                        <img class="mr-1"  src="https://colorlib.com/unite/wp-content/uploads/sites/7/2013/03/image-alignment-150x150.jpg" alt="150">
                    </div>
                    <div class="d-flex mt-1">
                        <img class="mr-1" src="https://colorlib.com/unite/wp-content/uploads/sites/7/2013/03/image-alignment-150x150.jpg" alt="150">
                        <img class="mr-1" src="https://colorlib.com/unite/wp-content/uploads/sites/7/2013/03/image-alignment-150x150.jpg" alt="150">
                    </div>
                </aside>
                <article class="container">
                    <header class="font-weight-bolder display-4">
                        Header
                    </header>
                    <p>
                        olorlib.com/unite/wp-content/uploadolorlib.com/unite/wp-content/uploadolorlib.com/unite/wp-content/upload
                        olorlib.com/unite/wp-content/uploadolorlib.com/unite/wp-content/uploadolorlib.com/unite/wp-content/upload
                        olorlib.com/unite/wp-content/uploadolorlib.com/unite/wp-content/uploadolorlib.com/unite/wp-content/upload
                    </p>
                    <p>
                        olorlib.com/unite/wp-content/uploadolorlib.com/unite/wp-content/uploadolorlib.com/unite/wp-content/upload
                    </p>
                    <a href="#" class="btn btn-primary">
                        Go to website
                    </a>
                </article>
            </li>
        </ul>
{{--        {{dd($html)}}--}}
    </main>
@stop
