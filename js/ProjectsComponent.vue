<template >
  <div class="content" >
    <ProjectsTemplate :key="$route.name+'Projects'"/>
  </div>
</template>
<script>
import * as projectServices from '../services/Projectservices.js';
import ProjectsTemplate from './projectsTemplateComponent';
export default {
  name:'projects',
    data(){
        return{
          route : null
        }
    },
    //   beforeRouteEnter (to, from, next) { 
    //   next(vm=>{
    //     vm.route = to.name;
    //   });
    //   },
    // beforeRouteLeave (to, from, next) { 
     
    //   next();
    //   },
    methods:{
    },
    components: {
    ProjectsTemplate,
  },
    mounted(){
        this.$Progress.start();
    },
    created(){
          if(this.$router['currentRoute']['query'] != null){
             this.$parent.query = this.$router['currentRoute']['query'];
        }
    },
    
}
</script>

<style>

</style>