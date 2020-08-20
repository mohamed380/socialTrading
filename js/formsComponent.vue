<template>
  <div style="z-index:99999">
        <div class="modal fade" id="signUpForm" tabindex="-1" role="dialog" aria-labelledby="signUpForm" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                      <div class="modal-header" style="border-bottom:none">
                            <button style="width:fit-content" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                      </div>
                      <div class="modal-body">
                        <form @submit.prevent="rejester" @keydown="signUpForm.onKeydown($event)">
                            <div class="form-group">
                              <label>Username</label>
                              <input v-model="signUpForm.name" type="text" name="name"
                                class="formInput form-control" :class="{ 'is-invalid': signUpForm.errors.has('name') }">
                              <has-error :form="signUpForm" field="name"></has-error>
                            </div>
                            <div class="form-group">
                              <label>Email</label>
                              <input v-model="signUpForm.email" type="text" name="email"
                                class="formInput form-control" :class="{ 'is-invalid': signUpForm.errors.has('email') }">
                              <has-error :form="signUpForm" field="email"></has-error>
                            </div>
                            <div class="form-group">
                              <label>Password</label>
                              <input v-model="signUpForm.password" type="password" name="password"
                                class="formInput form-control" :class="{ 'is-invalid': signUpForm.errors.has('password') }">
                              <has-error :form="signUpForm" field="password"></has-error>
                            </div>
                            <div class="form-group" >
                              <label>Confrim Password</label>
                              <input v-model="signUpForm.confirmPassword" type="password" name="confirmPassword"
                                class="formInput form-control" :class="{ 'is-invalid': signUpForm.errors.has('confirmPassword') }">
                              <has-error :form="signUpForm" field="confirmPassword"></has-error>
                            </div>
                            <div class=" row justify-content-center">
                              <button type="submit" class="submitButton" :disabled="loading">
                                  <div v-if="loading" id="spinner-grow" class="spinner-border spinner-border-sm" role="status" style="margin: 0px 9px;">
                                      <span class="sr-only" >Loading...</span>
                                  </div>
                                  <span v-if="!loading">submit</span>
                              </button>
                            </div>
                        </form>
                      </div>
                   
                </div>
            </div>
        </div>
        <div class="modal fade" id="loginForm" tabindex="-1" role="dialog" aria-labelledby="loginForm" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                      <div class="modal-header" style="border-bottom:none">
                            <button @click="clean" style="width:fit-content" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                      </div>
                      <div class="modal-body">
                        <div class="loginFormHeader">
                            <img :src="img" alt="userImg">
                        </div>
                        <form method="POST" @submit.prevent="login" @keydown="loginForm.onKeydown($event)" enctype="multipart/form-data">
                            <input type="hidden" name="_token" :value="csrf">
                            <div class="form-group">
                              <label>Email</label>
                              <input @change="getImg" v-model="loginForm.email" type="text" name="email" required
                                class="form-control formInput" :class="{ 'is-invalid': loginForm.errors.has('email') }">
                              <has-error :form="loginForm" field="email"></has-error>
                            </div>
                            <div class="form-group"  >
                              <label>Password</label>
                              <input v-model="loginForm.password" type="password" name="password" required
                                class="form-control formInput" :class="{ 'is-invalid': loginForm.errors.has('password') } ">
                              <has-error :form="loginForm" field="password"></has-error>
                            </div>
                            <div class=" row justify-content-center">
                              <button type="submit" class="submitButton" :disabled="loading">
                                  <div v-if="loading" id="spinner-grow" class="spinner-border spinner-border-sm" role="status" style="margin: 0px 9px;">
                                      <span class="sr-only" >Loading...</span>
                                  </div>
                                  <span v-if="!loading">login</span>
                              </button>
                            </div>
                        </form>
                      </div>
                </div>
            </div>
        </div>
  </div>
</template>

<script>
import Vue from 'vue'
import { Form, HasError, AlertError } from 'vform'
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
export default {
      data () {
    return {
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      loading: false,
      signUpForm: new Form({
        name: '',
        password: '',
        email:'',
        confirmPassword:'',
        remember: false
      }),
      loginForm: new Form({
        password: '',
        email:'',
      }),
      img: '/storage/logo.jpeg'
    }
  },
  methods:{
    async rejester()
    {
      this.loading = true;
      if(this.signUpForm.password == this.signUpForm.confirmPassword){
          await this.signUpForm.put('/singUp')
                .then(({ data }) => { 
                  this.loading = false;
                  this.user = data; $('.modal-backdrop').remove();
                    $('#signUpForm').modal('hide') 
                })
                .catch(({data})=>{ 
                      this.loading = false;
                });
      }else{
        this.loading = false;
           this.signUpForm.errors.errors = {
            'confirmPassword':['dose\'t match password!'],
          };
      }
 
    },

    async login(e)
    {
          this.loading = true;
          await this.loginForm.post('/logIn')
          .then(({ data }) => { 
              if(data){
                        this.loginForm.reset();
                        this.user = data; $('.modal-backdrop').remove(); $('#loginForm').modal('hide'); 
                      }
              else    {
                        this.loginForm.errors.errors = {
                          'password':['isn\'t correct!'],
                          'email' : ['isn\'t correct!']
                        };
                        this.loginForm.password ='';
                      }
                  this.loading = false; 
              })
        .catch(()=>{
                  this.loading = false; 
          });
    },

    clean(){
      this.loginForm.reset();
      this.signUpForm.reset();
      this.img = 'storage/logo.jpeg'
      $('label').css({'top':'30px'})
    },
    getImg(){
      if(this.loginForm.email !=null){
              axios.get('/getUserpp/'+this.loginForm.email).then(({data})=>{
        if(data){
          this.img = data.img;
        }else{
          this.img = 'storage/logo.jpeg'
        }
      });
      }

    }
    //  selectFile (e)
    //  {
    //       let file = e.target.files[0]
    //       let  reader = new FileReader();
    //       reader.onloadend = (file) => {
    //         this.signUpForm.img = reader.result;
    //       }
    //       reader.readAsDataURL(file)
    //  }
  },
  mounted(){
    EventBus.$on('updateLogInImg',()=>{
      this.img = '/storage/logo.jpeg'
    });
  }

}
</script>

<style>
.hasInvalidValue::before{
  bottom: 22px!important;
}
  .loginFormHeader{
    widows: 100%;
    justify-content: center;
    display: flex;
  }
  .loginFormHeader img{
    width: 50px;
    height: 50px;
    border-radius: 50px;
    object-fit: scale-down;
  }
  .modal-dialog{
    display: flex;
    justify-content: center;
  }
  .modal-content{
        width: 87%;
  }
  .modal-header{
        padding: 5px 1rem;
  }
  .modal-body{
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  .modal-body form{
    width: 75%;
  }
  .modal-content .form-group{
     margin-bottom: 0px;
    margin-bottom: 0px;
    position: relative;
  } 
  .modal-content .form-group .form-control{
    border: none;
    border-bottom: 1px solid #d2d2d2;
    border-radius:0px ;
    font-weight: 500;
    font-size: 13px;
  }
  
   .modal-content .form-group .form-control:focus{
    border: none;
    border-bottom: 1px solid #d2d2d2;
    border-radius:0px ;
    box-shadow: none;
  }

  .modal-content .form-group label{
        margin-bottom: 0px;
        transition: all .2s linear;
        left: 13px;
        top:30px;
        position: relative;
        font-weight: 500;
        font-size: 15px;
  }
  .has-val label{
    top: 10px!important;
    left: 13px!important;
  }
  .borderBottom::before{
    content: "";
    width: 0%;
    position: absolute;
    left: 0px;
    height: 2px;
    bottom: 0px;
    background: #6a7dfe;
    animation: borderBottomAnimation .2s ease-in-out 1 0s; 
    width: 100%;
    background: -webkit-linear-gradient(to right, #21d4fd, #b721ff);
    background: -o-linear-gradient(to right, #21d4fd, #b721ff);
    background: -moz-linear-gradient(to right, #21d4fd, #b721ff);
    background: linear-gradient(to right, #21d4fd, #b721ff);
}
  .submitButton{
    padding: 0px 29px 2px 29px;
    font-weight: 500;
    font-variant: all-small-caps;
    border-radius: 13px;
    background: white;
    border: 1px solid #d2d2d2;
    transition: all .2s linear;
    margin-top: 15px;
  }
  .submitButton:hover{
    background: #41d241;
    color: white;
  }
  .submitButton:focus{
    box-shadow: none;
    border: 1px solid #d2d2d2;
    outline: none;
  }
  @keyframes borderBottomAnimation{
    0%{width:0%}
    100%{width:100%}
  }
</style>