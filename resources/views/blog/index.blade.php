@extends('layouts.app',['title'=>'Blog Post'])
@section('content')
<div id="appCapsule">
  @include('blog.'.$id)

  <div class="section">
    <a href="/contact-us" class="btn btn-block btn-primary" data-bs-toggle="modal" data-bs-target="#actionSheetShare">
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