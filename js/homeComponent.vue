<template>
    <div class="gns" style="width:100%;height:100%">
        <div class="mainSliderContainer">
            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel" data-pause="false">
                <div class="carousel-inner">
                      <div style="position:relative" v-for="project in topProjects" :key="project.id" class="carousel-item" :class="{'active':project == topProjects[0]}">
                          <img class="projectImg" :src="'/storage/'+project.img" :alt="project.title">
                          <div class="topProjectContent">
                            <div class="ownerLink">
                                <div class="owner" v-if ="project.user != null">
                                    <router-link class="userLink" :to="{name:'visitProfile',params:{username : project.user.name} }">
                                        <img  :src="project.user.img" alt="">
                                    </router-link>
                                </div>
                                <div  v-else class="owner">
                                    <a href="/" class="userLink">
                                        <img src='storage/logo.jpg' alt="logo">
                                    </a> 
                                </div>
                            </div>
                            <router-link class="topProjectTitle" :to="{name:'project', params:{id: project.id,title: project.title.replace(/\s+/g, '-')}}">
                                    {{project.title}}
                            </router-link>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
</template>

<script>
import * as projectServices from '../services/Projectservices.js';
import forms from './formsComponent'
import AuthButtons from './AuthButtonsComponent'


export default {
    data(){
        return{
           topProjects : []
        }
    },
    methods:{
        topThreeProjects(){
            if(this.$root.topProjects == null){
                axios.get('HomeProjects').then( ({ data }) => {
                    this.topProjects = data;
                    this.$root.topProjects = data;
                });
            }else{
                this.topProjects = this.$root.topProjects; 
            }
        }
    },  
    components: {
    forms,
    AuthButtons
  },
    mounted(){
 window.Echo.join(`chat`)
    .here((users) => {
        console.log('xx');
        console.log(users);
    })
    .joining((user) => {
        console.log(user.name + ' joined');
    })
    .leaving((user) => {
        console.log(user.name + ' leaved');
    });
        this.$Progress.start();  
        this.topThreeProjects()
    },
    created(){
        this.$Progress.finish();
    }
}
</script>

<style>
    .carousel-item .projectImg{
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        background: #5bbb2d;
    }
    .carousel-item .projectImg::after{
        width: 100%;
        height: 100%;
    }
    .topProjectContent{
        position: absolute;
        left: 0px;
        bottom: 41px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        width: 100%;
        padding: 0px 20px;
    }
    .topProjectTitle{
    width: fit-content;
    border-radius: 20px;
    padding: 0px 8px 2px 8px;
    border: 1px solid gainsboro;
    color: white;
    background: rgba(0, 0, 0, .4);
    font-weight: 500;
    font-size: 11px;
    cursor: pointer;
    transition: all .1s linear;
    }
    .topProjectTitle:hover{
        text-decoration: none;
        color: white;
        background: rgba(0, 0, 0, .6);
    }
    .ownerLink{
        width: fit-content;
    }
    .owner img{
        width: 25px;
        height: 25px;
        border-radius: 25px;
        margin-right: 5px;
    }
</style>