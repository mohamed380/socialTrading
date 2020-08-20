<template  >
    <div v-if="profileuser"  class="content">
      <div class=" profileHeader">
          <div class="cover">
              <img :src="profileuser.cover" alt="">
          </div>
          <div class="pp">
                <img v-if="profileuser" :src="profileuser.img" class="loading" alt="">
                <div v-else class="loading loadingPP"></div>

                <p v-if="profileuser">{{profileuser.name}}</p>
                <span v-else class="loading" style="width:130px; height:20px;border-radius:3px"></span>
            
          </div>
          <div class="plincks">
                  <a href=""><i class="fab fa-facebook-f" style="color:#4267b2;"></i></a>
                 <a href=""><i class="fab fa-twitter" style="color:rgb(29, 161, 242)"></i></a>
                  <a href=""><i class="fab fa-linkedin-in" style="color:#0073b1;"></i></a>
          </div>
      </div>
      <div v-if="profileuser" class="tabs pButtons">
                <span>
                    <i class="fas fa-users"></i>
                    <p v-if="followers">{{followers.length}}</p>
                    <p v-else class="numberLoading">0</p>
                    <p>Followers</p>
                </span>
                <span>
                    <i class="far fa-eye"></i>
                    <p>{{views}}</p> 
                    <p>Views</p>
                </span>
                <span>
                    <i class="fas fa-solar-panel"></i>
                    <p v-if="projects">{{projectsCount}}</p>
                    <p v-else class="numberLoading">0</p>
                    <p>Projects</p>
                 </span>
                <followButton key="profileFollow" />
      </div>
      <div v-else class="tabs pButtons">
          <span class="loading"></span>
          <span class="loading"></span>
          <span class="loading"></span>
          <span class="loading"></span>
      </div>
      <Projects v-if="this.projects"/>
  </div>
</template>

<script>

import * as projectServices from '../services/Projectservices.js';
import Projects from './projectsTemplateComponent';
import followButton from './followButtonComponent';
export default {  
    data(){
        return{
          projects:  null,
          followers: null,
          following: null,
          profileuser : null,
          views:0,
          projectsCount:0
        }
    },
    beforeRouteLeave (to, from, next){ 
      if(to.name != "project"){
        this.$root.state = true;
        this.$root.projects = null;
        this.$root.profile = null;
      }
      else{
          this.$root.profile = {
          'following' : this.following,
          'followers' : this.followers,
          'profileuser' : this.profileuser
        };
      }
      next();
      },
    methods:{
      async getUserData()
       { 
         this.clear();
              let username = ''; 
              if(this.$route.name == 'profile'){
                    await axios.get('/currentUser').then(({data})=>{
                    this.user = data;
                    username = data.name;
                });
              }else{
                username = this.$route.params.username;
              }
              if(username != ''){
                  await axios.get('/userData/'+username).then(({data})=>{
                      this.projects = data.projects;
                      this.followers = data.user.follower;
                      this.following = data.user.following;
                      this.profileuser = data.user;
                    });
                EventBus.$emit('followingState', this.profileuser);
                this.count();
              }else{
                this.$router.push({name:'home'});
              }
       },

      count()
      {
          if(this.projects){
            this.projects.forEach(projectsChunks => {
              if(projectsChunks.projects.length>0)
                {projectsChunks.projects.forEach(project=>{
                  this.projectsCount++;
                this.views += project.view_count;
                })}
            });   
          }
             
      },

      clear()
      {
        this.projects =  null;
        this.followers =  null;
        this.following = null;
        this.profileuser = null;
        this.views = 0;
        this.projectsCount = 0;
        EventBus.$emit('followingState', this.profileuser);
        EventBus.$emit('updateProjects', this.projects);
      }
    },

    components: {
    Projects,
    followButton
  },
    mounted(){
        // this.getUserData();
        this.$Progress.start();
    },
    created(){  
      this.$Progress.finish();
    },
   beforeRouteEnter (to, from, next) {
     next(vm=>{
       vm.getUserData();
     });
    }
}
  
</script>

<style>

</style>