<template>
<div class="content" style="padding:26px;">
    <div class="modal fade" id="reportProjectForm" tabindex="-1" role="dialog" aria-labelledby="reportProjectForm" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                      <div class="modal-header" style="border-bottom:none">
                            <button  style="width:fit-content" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                      </div>
                      <div class="modal-body">
                        <form method="POST" @submit.prevent="sendComplain"  enctype="multipart/form-data" style="    font-weight: 500;">
                            <div v-if="!complains" class="loadingcomplains">
                                    <span class="loading" ></span>
                                    <span class="loading" ></span>
                                    <span class="loading" ></span>
                                    <span class="loading" ></span>
                                    <span class="loading" ></span>
                            </div>
                            <template v-else>
                            <div class="form-check" v-for="(complain,index) in complains" :key="'complain'+index"  >
                              <input v-model="complainForm.complain" type="radio" :value="complain" name="complain"  class="form-check-input" >
                              <label class="form-check-label">{{complain}}</label>
                            </div>
                            <div class="form-check" >
                              <input v-model="complainForm.complain" type="radio" value="more" name="complain"  class="form-check-input" >
                              <label class="form-check-label">Something else</label>
                            </div>
                            <div  class="from-group mt-2" style="    max-width: 100%;">
                                <textarea required name="complain" :disabled="complainForm.complain!='more'" cols="40" rows="5" placeholder="Explain Your complain" v-model="explaindComplain"></textarea>
                            </div>
                            <div class=" row justify-content-center">
                              <button type="submit" class="submitButton" :disabled="loading">
                                  <div v-if="loading" id="spinner-grow" class="spinner-border spinner-border-sm" role="status" style="margin: 0px 9px;">
                                      <span class="sr-only" >Loading...</span>
                                  </div>
                                  <span v-if="!loading">Submit</span>
                              </button>
                            </div>
                            </template>
                        </form>
                      </div>
                </div>
            </div>
        </div>
    <div class="Window">
            <span class="closeWindow">
                <i class="fa fa-arrow-left "></i>
            </span>
            <div v-if="project == null"  id="spinner-grow" class="spinner-grow text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <div v-else class="projectWidow container-fluid mt-5">
                <div v-if="project" class="mainProject row">
                    <div class="porjectImageBar col-md-12 col-sm-12 col-lg-7" style="padding: 0px 5px 5px;display:flex;justify-content: center;">
                        <v-zoomer ref="zoomer" style="width:500px;height:500px;">
                            <img :src="'/storage/'+selectedImage"  style="object-fit:contain;height:100%;width: 100%;">
                        </v-zoomer>
                        <div class="manageProjectImage">
                            <i class="fas fa-search-plus" @click="$refs.zoomer.zoomIn()"></i>
                            <i class="fas fa-search-minus" @click="$refs.zoomer.zoomOut()"></i>
                        </div>
                    </div>

                    <div class="porjectDetailsBar col-md-12 col-sm-12 col-lg-5">
                        <div class="topDetialsBar row">
                            <div class="projectOwner col-12">
                                <div class="userFollowLink" v-if ="project.user != null">
                                    <router-link class="userLink" :to="{name:'visitProfile',params:{username : project.user.name} }">
                                        <img  :src="project.user.img" alt="">
                                        <p>{{project.user.name}}</p>
                                    </router-link>
                                    <followButton key="viewProjectFollow" />

                                </div>
                                <div  v-else class="userFollowLink">
                                    <a href="/" class="userLink">
                                        <img src="/storage/admins/aKjlncq72CDcT738Du0VPudkLNlRhOs7X6eM143D.jpeg" alt="">
                                        <p style="margin-right:5px">wtp</p>
                                    </a> 
                                </div>
                            </div>
                            <div class="buttons col-12 row" :id="project.id">
                                <span data-before="Copy" class="copy" style="position:relative"><i class="fas fa-clipboard" style="font-size:17px" ></i></span>
                                <span><i class="fas fa-file-archive"></i> <p>{{Number((project.size*1024).toFixed(2))}} MB</p></span>
                                <span><i class="fas fa-eye"></i> <p >{{project.view_count}}</p></span>
                                <button class="likeProject" @click="likeProject($event)">
                                    <i v-if ="like" class="fas fa-heart" style="color:#ff7c7c;font-size: 14px;"></i>
                                    <i v-else class="fas fa-heart" style="font-size: 14px;"></i>
                                    <p >{{project.like_count}}</p>
                                </button>
                                <button @click="download" class="btn btn-success btn-sm" style="background:green">
                                        <i  class="fas" :class="{'fa-cog':getDownloadApprove,'fa-download':!getDownloadApprove}"></i>
                                        <p>{{project.download_count}}</p>
                                </button>
                                <a v-if="project.title" style="display:none" id='downloadProjectLink' :href="'/getDownload/'+project.id+'/'+project.title.replace(/\s+/g, '-')"></a>
                            </div>
                        </div>

                        <div class="details">
                            <span class="title"><i class="fas fa-paperclip"></i><p>Title</p></span>
                            <h5 class="pl">{{project.title}}</h5>
                            <hr>
                            <div class="showMoreContainer">
                                <span class="title"><i class="fas fa-align-left"></i><p>Description</p></span>
                                <button class="btn btn-primary btn-sm descriptionToggleShow">Show more</button>
                            </div>
                            <div class="showless descriptionContainer">
                                <pre class="pl">{{project.description}}</pre>
                            </div>
                            <hr>
                            <span class="title"><i class="fas fa-hashtag"></i><p>Hashtags</p></span>
                            <div class="projectTags">
                                <li v-for="tag in project.tag" :key="tag.id" @click="searchByTag(tag.title)">
                                    {{ tag.title }}
                                </li>
                            </div>
                            <hr>
                            <span class="title"><i class="fas fa-image"></i><p>Images</p></span>
                            <div class="porjectImages">
                                <div @click="changeImagePath(img.path)" v-for="(img,index) in project.image" :key="index+'img'" :class="{'selectedImage':selectedImage == img.path}" >
                                    <img :src="'/storage/'+img.path" alt="index">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row justify-content-end" style="width:100%">
                            <button @click="getReportData" class="reportProjectButton" data-toggle="modal" data-target="#reportProjectForm">Report</button>
                    </div>
                </div>
                <RelatedProjects v-if="project.id" />
            </div>
    </div>
</div>

</template>

<script>
import * as projectServices from '../services/Projectservices.js';
import followButton from './followButtonComponent'
import RelatedProjects from './projectsTemplateComponent'

 export default {
  name:'projectView',
    components: {
        followButton,
        RelatedProjects
  },   
    data(){
        return{
            project:{},
            selectedImage:null,
            like:false,
            following:false,
            getDownloadApprove:false,
            complains: null,
            complainForm: new Form({
                userid:null,
                projectid:null,
                complain:null
            }),
            loading:false,
            explaindComplain:''
        }
    },
    methods:{
    searchByTag(tagTitle){
        EventBus.$emit('searchByTag',tagTitle)
    },

    async getProject(id)
    {
        this.$Progress.start();
        let result = await projectServices.getProject(id);
        this.project = result.project;
        this.selectedImage = this.project.img;
        this.like = result.like;
        this.$Progress.finish()
        setTimeout(()=>{
        if(this.project.user){
            EventBus.$emit('followingState', this.project.user);
        }
        },100);
    },

    changeImagePath(path){
        this.selectedImage = path;
    },
    download(){
        this.getDownloadApprove = true;
        axios.get('/approveDownload').then(({data})=>{
                        if(data){
                            document.getElementById('downloadProjectLink').click();
                        }else{
                            alert('exceed daily limit');
                        }      
                })
                .catch(({data})=>{
                console.log('something went wrong!');
                });
                this.getDownloadApprove =false;
    },

    likeProject(event){
        if(!this.like){
            this.like = true;
        }else{
            this.like = false
        }
        var likesNumber =  parseInt(document.getElementsByClassName('likeProject')[0].children[1].innerHTML)?  parseInt(document.getElementsByClassName('likeProject')[0].children[1].innerHTML):0;
        axios.get('/likeProject/'+this.$route.params.id).then( ({ data }) => {
        if(data){
            if(data == 'liked'){
            this.like = true;
            document.getElementsByClassName('likeProject')[0].children[1].innerHTML = likesNumber+1;
            }else{
            this.like = false;
            document.getElementsByClassName('likeProject')[0].children[1].innerHTML = likesNumber-1;
            }
        }else{
            this.like = false
        }
        });
    },

    copyHref(){
    },

    getReportData()
    {
        if(!this.complains){
                axios.get('/getReportsOptions').then((data)=>{
                    this.complains = data.data;

                }).catch(()=>{
                })
            }
    },

    sendComplain(){
        $('.modal-backdrop').remove(); $('#reportProjectForm').modal('hide');
        this.complainForm.userid = this.user.id;
        this.complainForm.projectid = this.project.id;
        if(this.complainForm.complain == "more"){
            this.complainForm.complain = this.explaindComplain;
        }
            if(this.user.id==null){
                    Toast.fire({
            icon: 'warning',
            title: 'You Have To Be Logged In!'
            }) 
            
            }else{
                    this.complainForm.post('/submitReport').then((data)=>{
            
            if(data){
            Toast.fire({
            icon: 'success',
            title: 'Your report sent Successfully!'
            }) 
            }else{
                    Toast.fire({
            icon: 'error',
            title: 'Something went wrong!'
            }) 
            }
        }).catch(()=>{
            Toast.fire({
            icon: 'error',
            title: 'Something went wrong!'
            }) 
        });
            }
        
            this.complainForm.reset();
        this.explaindComplain = '';
    }
    },

    async mounted(){
        this.$Progress.start();
        this.$root.state = false;
        await this.getProject(this.$route.params.id);
    },
    created(){  
        this.$Progress.finish();
    },beforeRouteUpdate (to, from, next) {
        this.getProject(to.params.id);
        next()
    }
    }
</script>

<style>
.manageProjectImage{
    padding: 5px 5px;
    right: 15px;
    top: 0px;
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
  color: gray;
  background: white;
  border-radius: 3px;
}
.manageProjectImage i{
    cursor: pointer;
    margin: 0px 5px;
    border-radius: 4px;
    padding: 2px 5px;
    box-shadow: 2px 3px 6px 0px #00000061;

}
.manageProjectImage i:hover{
    box-shadow: 2px 3px 6px 0px #00000094;
    transition: all .2s linear;
}

.selectedImage{
        border: 2px solid #23a03f;
        padding: 7px;
        transition: all .1s linear;
}

 .porjectImages{
    display: flex;
    align-items: center;
} 

.porjectImages div{
    max-width:100px;
    height:100px;
    border-radius: 3px;
    margin:3px;
    cursor: pointer;
}
.porjectImages div img{
    max-width:100%;
    height: 100%;
   border-radius: inherit;
}
.reportProjectButton{
    background: transparent;
    padding: 0px 16px;
    line-height: 14px;
    border-radius: 8px;
    color: #3232dd;
    font-size: 14px;
    border: 1px solid transparent;
}
.loadingcomplains{
        display: flex;
    flex-direction: column;
}
.loadingcomplains span{
        width: 100%;
    height: 20px;
    margin-bottom: 5px;
    border-radius: 3px;
}
button:focus{
    outline: none!important;
}

.showMoreContainer{
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.showless{
    max-height: 40px;
    overflow: hidden;
    transition: all .3s linear;
    position: relative;
}
.showmore{
    max-height: 100px;
    transition: all .3s linear;
    position: relative;
}

.showmore pre{
     overflow: auto;
     max-height: inherit;
}
.showless::before{
    content: '';
    position: absolute;
    bottom: 0px;
    width: 100%;
    height: 20px;
    background: #ffffff99;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}
.showless pre{
    height: inherit;
}

</style>