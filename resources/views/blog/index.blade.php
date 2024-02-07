@extends('layouts.app',['title'=>'Blog Post'])
@section('content')
<div id="appCapsule">
  @include('blog.'.$id)

  @if(session('show_ad'))
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2768566574593657" crossorigin="anonymous"></script>
        <ins class="adsbygoogle" style="display:block" data-ad-format="fluid" data-ad-layout-key="-hv+e-p-44+a0" data-ad-client="ca-pub-2768566574593657" data-ad-slot="6673501127"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        @endif

  <div class="section">
  <a href="https://tawk.to/svk" class="btn btn-block btn-primary">
      <ion-icon name="car-outline"></ion-icon> Book now
    </a>
  </div>


  <div class="section mt-3">
    <h2>Related Posts</h2>
    <div id="app" class="row mt-3">

      <div v-for="item in blogs" v-if="item.id!=id" class="col-6 mb-2">
        <a :href="item.link">
          <div class="blog-card">
            <img :src="item.img" alt="image" class="imaged w-100">
            <div class="text">
              <h4 v-html="item.title" class="title"></h4>
            </div>
          </div>
        </a>
      </div>


    </div>
  </div>
  @if(session('show_ad'))
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2768566574593657" crossorigin="anonymous"></script>
        <ins class="adsbygoogle" style="display:block" data-ad-format="fluid" data-ad-layout-key="-hv+e-p-44+a0" data-ad-client="ca-pub-2768566574593657" data-ad-slot="6673501127"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        @endif


</div>
@endsection

@section('footer')
<script>
  new Vue({
    el: '#app',
    data() {
      return {
        blogs: [],
        id: '{{$id}}',
      }
    },
    mounted() {
      this.blogs = JSON.parse('{!!$blogs!!}');
    },
    methods: {

    }
  })
</script>
@endsection
