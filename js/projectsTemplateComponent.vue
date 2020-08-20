<template>
<div >
  <div v-if="chunks == null" class="noProjects">
    <div class="spinner-border" style="width: 5rem; height: 5rem;color:gainsboro" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>

  <template v-else>
      <template v-if="chunks.length>0"> 
          <div class="card-columns projects-container">
            <template  v-for="chunk in chunks">
              <router-link v-for="project in chunk.projects" :key="project.id" :to="{name:'project', params:{id: project.id,title: project.title.replace(/\s+/g, '-')}}">
                <div class="card project"  >
                  <img style="" :src="'/storage/'+project.img" class="card-img-top" width="100%" height="100%" >
                  <div class="card-body">
                    <p class="card-title project-title">{{project.title}}</p>
                    <div class="projectInfo">
                        <div class="projectOwner">
                          <div v-if ="project.user != null">
                              <img  :src="project.user.img" alt="">
                              <p>{{project.user.name}}</p>
                          </div>
                          <div  v-else >
                              <img src="/storage/admins/aKjlncq72CDcT738Du0VPudkLNlRhOs7X6eM143D.jpeg" alt="">
                              <p>wtp</p>
                          </div>
                        </div>
                        <div class="icons">
                        <span> <i class="fas fa-download"></i><p>{{project.download_count }}</p></span>
                          <span> <i class="fas fa-heart"></i><p>{{project.like_count}}</p></span>
                          <span><i class="fas fa-eye"></i> <p>{{project.view_count}}</p></span>
                        </div>
                        
                    </div>
                    <p class="projectDate">{{project.created_at|newDate}}</p>
                  </div>
                </div>
              </router-link>
             
                  <div class="card moreAbout" v-if="chunk.category" :key="chunk.category.title" @click="searchByCategory(chunk.category.title)">
                    <img style="" :src="'/'+chunk.category.img" class="card-img-top" width="100%"  height="100%" >
                    <div class="card-body">
                      <p class="card-title project-title">{{chunk.category.title}}</p>
                    </div>
                  </div>
              
            </template>
          </div>
          <div style="margin:0px;padding:5px 0px 20px 0px" class="row justify-content-center " >
              <span class="loadingState">
                 <div v-if="loading" id="spinner-grow" class="spinner-border spinner-border-sm" role="status">
                                <span class="sr-only">Loading...</span>
                  </div>
                  <span v-else-if="!more">No More</span>
              </span>
          </div>
      </template>
      <div v-if="chunks.length==0" class="noProjects">
                <span >
                  <i class="fas fa-solar-panel" style="font-size: 154px;"></i>
                  <h3>No Projects Yet!</h3> 
                </span>
      </div>
  </template>

</div>
</template>

<script>

import * as projectServices from '../services/Projectservices.js';
export default {
  name:'projectsTemplate',
    data(){
        return{
          chunks:null,
          more:true,
          likes : [],
          loading:false,
          search:true,
          query:{
              categories:[],
              subcategories:[],
              programs:[],
              tags:[],
              type:'any',
              title:'null',
            },
        }
    },
    methods:{
      searchByCategory(title){
         EventBus.$emit('searchByCategory', title);
      },

      initializer()
      {
          if(this.$router['currentRoute']['query']){
              let routeQuery = this.$router['currentRoute']['query'];
              this.query['categories'] = this.query['categories'].concat(routeQuery['categories']);
              this.query['subcategories'] = this.query['subcategories'].concat(routeQuery['subcategories']);
              this.query['programs'] = this.query['programs'].concat(routeQuery['programs']);
              this.query['tags'] = this.query['tags'].concat(routeQuery['tags']);
              this.query['type'] = routeQuery['type']?routeQuery['type']:'any';
              this.query['title'] = routeQuery['title']?routeQuery['title']:'null';
            }
      },

      async following()
      { 
          let result = await projectServices.followingProjects(this.chunks?this.chunks.length:0);
          this.chunks = this.chunks?this.chunks:[];
            result.forEach((pr)=>{
                    this.chunks.push(pr);
                });
           if(result.length==0){
            this.more = false;
          }
          return true;
      },

      async explore()
      { 
          let result = await projectServices.explore(this.chunks?this.chunks.length:0);
            this.chunks = this.chunks?this.chunks:[];
          result.forEach((pr)=>{
                    this.chunks.push(pr);
                });
          if(result.length==0){
            this.more = false;
          }
            return true;
      },
      
      async relatedProjects(id){
          let result = await projectServices.getRelatedProjects(id,this.chunks?this.chunks.length:0);
          this.chunks = this.chunks?this.chunks:[];
          result.forEach((pr)=>{
                    this.chunks.push(pr);
                });
          if(result.length==0){
            this.more = false;
          }
            return true;
      },

      async searchProjects(query)
      {
        let result = [];
        if(this.$root.loadSearch){
          this.chunks = this.chunks? this.chunks: [];
              result = await projectServices.searchProjects(query,this.chunks.length);
              result.forEach((pr)=>{
                this.chunks.push(pr);
              });
              this.$root.loadSearch = true;
          if(result.length==0){
                this.more = false;
          }
        }else{
            this.chunks = [];
            result = await projectServices.searchProjects(query,this.chunks.length);
            this.chunks = result;
            this.$root.loadSearch = true;
          if(result.length==0){
                this.more = false;
          }
        }
        
        return true;
     
      }, 

     async build()
      {
        this.loading = true;
        if(this.$route.name=='explore'){
            await this.explore();
        }
        if(this.$route.name == 'searchProjects'){
          if(!this.$root.loadSearch){
            this.initializer();
          }
           await this.searchProjects(this.query);
        }
        if(this.$route.name == 'following'){
           await this.following();
        }
        if(this.$route.name=='visitProfile' || this.$route.name=='profile'){
            this.chunks = this.$parent.projects;
        }
        if(this.$route.name == 'project'){
          if(this.$parent.project){
            await this.relatedProjects(this.$parent.project.id);
          }
        }
        this.$Progress.finish();
        this.loading =false;
      },

     async loadMoreProjectsEvent()
      {
        if(this.more && !this.loading){
            this.loading = true;
            await this.build();
            this.loading = false;
        }
      }         

    },
    
    mounted(){
      //  this.loadMoreProjectsEvent =  _.debounce(this.loadMoreProjectsEvent, 500);
        EventBus.$on('searchByQuery', (query) => 
        {
        this.$Progress.start();
          this.query = query;
        this.more = true;
          this.searchProjects(query);
        this.$Progress.finish();
        });
        EventBus.$on('updateProjects', (projects) => 
        {
          this.chunks = projects;
        });
        
        window.onscroll = () =>
        {
          var d = document.documentElement;
          var height = Math.max(
          document.body.scrollHeight, d.scrollHeight,
          document.body.offsetHeight, d.offsetHeight,
          document.body.clientHeight, d.clientHeight
          );
          var offset = d.scrollTop + d.clientHeight;
          if (offset >= height -1500) {
            this.loadMoreProjectsEvent()
            }
        }
         this.build();
    },
    created(){
 
    
    },
    beforeDestroy(to, from , next){
      EventBus.$off('searchByQuery');
      EventBus.$off('updateProjects')
    }
}

</script>

<style>
.moreAbout{
  max-height: 105px;
}
.moreAbout .card-body{
  display: flex!important;
}
.moreAbout img{
  max-height: inherit;
  object-fit: cover;
}
.loadingState{
  font-weight: 500;
  color: gray;
}
</style>