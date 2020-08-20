require('./bootstrap');
import Vue from 'vue';
import { Form, HasError, AlertError } from 'vform'
import { objectToFormData } from 'object-to-formdata';
import VueChatScroll from 'vue-chat-scroll'
import Swal from 'sweetalert2'
import VueRouter from 'vue-router';
import VueZoomer from 'vue-zoomer';
import moment from 'moment';
import VueProgressBar from 'vue-progressbar'

// Components ==========================================================================

import profile from './components/userProfileComponent'
import home from './components/homeComponent';
import projectView from './components/projectViewComponent';
import searchBox from './components/searchBoxComponent';
import AuthButtons from './components/AuthButtonsComponent';
import Projects from './components/ProjectsComponent';
import dashboard from './components/dashboard/dashboardComponent';
import dashboardHome from './components/dashboard/dashboardHomeComponent';
import manageProjects from './components/dashboard/manageProjectsComponent';
import manageProfile from './components/dashboard/manageProfileComponent';

// Components ==========================================================================


window.Vue = require('vue');
Vue.use(VueChatScroll)
window.Form = Form
window.Swal = Swal;
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.Toast = Toast
window.objectToFormData = objectToFormData;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
window.EventBus = new Vue();
Vue.use(VueRouter)
Vue.use(VueZoomer)
window.VueZoomer = VueZoomer;
let routes =[
  { name:'project',
    path:'/project/:id/:title',
    component:projectView
  },
  {
    name : 'dashboard',
    path : '/dashboard',
    redirect: '/dashboard/home',
    component : dashboard,
    children: [
      { 
        path : 'home',
        name : 'dashboardHome',
        component : dashboardHome
      },
      {
        path : 'projects',
        name : 'dashboardProjects',
        component :  manageProjects
      },
      {
        path : 'profile',
        name : 'dashboardProfile',
        component : manageProfile
      }
    ],

  },
  { name:'home',
    path: '/',
    component:home
  },
  {
    path: '/searchProjects/:query?',
    name: 'searchProjects',
    component: Projects,
    props:true
  },
  {
    path: '/Profile/:username',
    name: 'visitProfile',
    component: profile,
    props: true
  },
  {
    path: '/profile',
    name: 'profile',
    component: profile,
  },
  {
    path: '/explore',
    name: 'explore',
    component: Projects
  },
  {
    path: '/following',
    name: 'following',
    component: Projects
  }
 
  
]
const router = new VueRouter({
    mode:'history',
    routes
})
router.beforeEach((to, from ,next)=>{
  window.scrollTo(0, 0)
  next()
})
const options = {
    color: 'red',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
      speed: '0.1s',
      opacity: '0.6s',
      termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
  }
  
Vue.use(VueProgressBar, options)
Vue.component('auth-buttons',AuthButtons);
Vue.component('search-box',searchBox);
Vue.filter('newDate', function(date){
    return moment(date, "YYYY-MM-DD HH:mm:ss").add(2,'hours').fromNow();
});

Vue.filter('capital' , function(name){
    return name.charAt(0).toUpperCase() + name.slice(1);
});
Vue.filter('count' , function(number){
    return number > 0 ? number : 0;
});
window.axios.defaults.headers.common = {
  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
  'X-Requested-With': 'XMLHttpRequest'
};


let userData = new Vue({
  data: { user: {} }
});
Vue.mixin({
  computed: {
    user: {
      get: function () { return userData.$data.user },
      set: function (user) { userData.$data.user = user; }
    }
  }
})

Vue.component('button-counter', {
    data: function () {
      return {
        count: 0
      }
    },
    template: '<button v-on:click="count++">You clicked me {{ count }} times.</button>'
  });
  const plugin = {
    showWindow(){
      document.getElementsByClassName("Window").style.display='block';
    }
}
  Vue.use(plugin)


const app = new Vue({
    el: '#app',
    router,
    data:{
     
      loadSearch: true,
      topProjects:null,
      state:true,
      categories:[],
      subCategrories:[],
      programs:[],
      profile: null,
      projects:null,
      likes:[],
    },
    component:{
      AuthButtons
    },
    methods:{
     async currentUser()
        {   
          await  axios.get('/currentUser').then(({data})=>{
                if(!data){
                    if(this.$route.name == "profile" || this.$route.name == "dashboardHome"||
                    this.$route.name == "dashboardProjects" || this.$route.name == "dashboardProfile"
                    ){
                      EventBus.$emit('unAuthorizedAccess')
                      this.$router.push({name:'home'});
                    }
                    this.user = {}
                    return false;
                }
            this.user = data;
 
            return true;
            }).catch(()=>{
                    this.user = {}
                    return false;
            });
        }
    },
  mounted(){
    EventBus.$on('unAuthorizedAccess',()=>{
      Toast.fire({
        icon: 'warning',
        title: 'You need to be logged in!'
    });
    });
  },
  created(){
   
  }
});
