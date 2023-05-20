@extends('layouts.app',['title'=>'Blogs'])
@section('content')
<div id="appCapsule" class="full-height">





  <div class="section mt-3">
    <div id="app" class="row mt-3">
      <div v-for="item in blogs" class="col-6 mb-2">
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