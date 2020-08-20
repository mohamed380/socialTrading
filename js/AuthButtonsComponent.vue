<template>
<div>
    <forms />
    <template v-if="user.id">
        <div class="chatIcon">
            <span class="reddot" v-if="chatNotifications.length > 0"></span>
            <template v-if="!WholeChatNotifications">
                <i v-if="message.message == ''"  class="toggleChat far fa-comment-alt" ></i>
                <i v-else @click="sendMessage" class="far fa-paper-plane"></i>
            </template>
                <li v-else class="realTimeChatNoti toggleChat" @click="getSpecificChat(WholeChatNotifications)">
                    <img :src="WholeChatNotifications.sender.img" :alt="WholeChatNotifications.sender.name" style="">
                    <span>{{WholeChatNotifications.message.message}}</span>
                </li>
        </div>
        <div class="chatBox row">
            <div class="followingChats col-xl-3 col-lg-3 col-md-3 col-sm-12">
                <!-- 'firstChat': (chatNotifications.findIndex(e => e.message.sourceID == user.id) +1) && user.id != currentChat.id, -->
                <li  :class="{ 'currentChat':chat.id == currentChat.id,'firstChat': (chatNotifications.findIndex(e => e == chat.id)+1  ) }" v-for="chat in chatList" :key="chat.id" @click="getChat(chat,null)">
                        <img :src="chat.user[0].img" :alt="chat.user[0].name"> 
                        <span>{{chat.user[0].name}}</span>
                </li>
                    <hr>
                <li :class="{'currentChat':user.id == currentChat.id }" v-for="user in user.following" :key="user.id" @click="getChat(null,user.id)">
                    <img :src="user.img" :alt="user.name" >
                    <span>{{user.name}}</span>
                </li>
            </div>
            
            <div  class="chatBody col-xl-9 col-lg-9 col-md-9 col-sm-12" style="padding:0px" >
               <template v-if="currentChat.id">
                    <div class="chatHeader">
                        <div>
                            <img :src="currentChat.user[0].img" :alt="currentChat.user[0].name">
                            <router-link style="text-decoration:none;color: white;font-weight: 500;" :to="{name:'visitProfile',params:{username : currentChat.user[0].name} }">{{currentChat.user[0].name}}</router-link>
                        </div>
                        <i class="fas fa-times-circle" @click="closeChat"></i>
                    </div>
                    <div class="chatMessages" id="chatMessages"  v-chat-scroll="{always: false, smooth: true, scrollonremoved:true, smoothonremoved: false}" @v-chat-scroll-top-reached="loadMoreMsgs()">
                        <span class="loadingMore" v-if="loadingMessages">
                            <div  id="spinner-grow" class="spinner-border spinner-border-sm" role="status" >
                                      <span class="sr-only" >Loading...</span>
                             </div>
                        </span>
                        <span class="loadingMore" v-if="noMoreMessages">No more messages</span>

                        <li  :class="{'floatRight':message.sourceID == user.id}" :key="message.id" v-for="message in chatMessages" >
                            <span class="message">{{message.message}}</span>
                            <span>{{message.created_at|newDate}}</span>
                        </li>

                        <span class="chatState floatRight"  v-if="(chatMessages[chatMessages.length-1].sourceID == user.id) && !participantTypingState" style="padding:0px">
                            <template v-if="participantSeenState">
                               <p><i class="fas fa-eye"></i> seen {{chatMessages[chatMessages.length-1].updated_at|newDate}}</p>
                            </template>
                            <template v-else><p><i class="fas fa-check"></i> sent </p></template>
                        </span>

                        <div class="ticontainer" v-if="participantTypingState">
                            <div class="tiblock">
                                <div class="tidot"></div>
                                <div class="tidot"></div>
                                <div class="tidot"></div>
                            </div>
                        </div>
                        
                    </div>
                    <form @submit.prevent="sendMessage">
                        <input  autocomplete="off" v-model="message.message" type="text" name="message" placeholder="Type" @keyup="updateTypingSate" class="messageInput">    
                    </form>
                </template>
                <div v-else-if="loadingChat" class="loadingChat">
                    <div  id="spinner-grow" class="spinner-border spinner-border-sm" role="status" style="margin: 0px 9px;">
                                      <span class="sr-only" >Loading...</span>
                     </div>
                </div>
                <div class="chooseChat" v-else>
                    <img src='/storage/logo.jpeg' alt="" style="width:60px;60px">
                    <span v-if="user.following.length > 0" >Choose one of the following and start chating </span>
                    <span  v-else > Start Follow People To Chat With </span>
                </div>
            </div>
           
        </div>
    </template>
  
    <div class="tabs homeTabs">
        <router-link to="/"><span><i class="fas fa-home"></i><p>Home</p></span></router-link>
        <router-link :to="{name:'explore'}"> <span  ><i class="fab fa-wpexplorer" style="font-weight:600"></i><p>Explore</p></span></router-link>
        <router-link :to="{name:'following'}" v-if="this.user.id"><span><i class="fas fa-users"></i><p>Following</p></span></router-link>
    </div>
  <div class="authButtons">
      <i v-if="this.user.id == null" class="before fas fa-user showAuthButtons"></i>

        <div class="userColumnsbanale" v-if="this.user.id == null">
        <div style="display:flex;flex-direction:column">
            <button  type="button" class="btn btn-primary authButton" data-toggle="modal" data-target="#loginForm">
                <i class="fas fa-door-closed"></i>
                <p>login</p>
            </button>
            <button  type="button" class="btn btn-primary authButton" data-toggle="modal" data-target="#signUpForm">
                <i class="fas fa-user-plus"></i>
                <p>Sign Up</p>
            </button>
        </div>
        </div>
        <div v-else class="userColumnsbanale">
            <div class="userBanaleIcons">
                <i class="before fas fa-plus newProject newProjectButton" style="color:white" @click="navigateToAddProject" v-if="this.user.id != null"> </i>
                <i  class="before fas fa-user showAuthButtons"></i>
                <i class="before fas fa-bell notifications" @click="toggleShowNoti" :class="{'notify':notify==true}" v-if="this.user.id != null">
                    <span v-if="notificationCount>0" class="reddot"></span>    
                 </i>
                <div  class="notificationCenter">
                    <h5 style="border-bottom:1px solid  #e2e2e2;text-align:center;margin-bottom: 3px;padding: 5px 0px 7px 0px;color:#414141">Notifications</h5>
                    <ul id="notificationList" class="notificationList" v-if="notifications.length>0" @scroll="loadMoreNoti()">
                        <template v-for="notification in notifications">
                            <li v-if="notification.type == notificationTypes.liked" :key="notification.id">
                                <img :src="notification.user.userImg" :alt="notification.user.username">
                                <span> 
                                    <router-link :to="{name:'visitProfile',params:{username : notification.user.username} }">
                                    @{{notification.user.username}}
                                    </router-link> <p> liked your Project {{notification.project.title}} </p></span>
                            </li>
                            <li  v-else-if="notification.type == notificationTypes.followed" :key="notification.id">
                                <img :src="notification.user.userImg" :alt="notification.user.username">
                                <span> 
                                    <router-link :to="{name:'visitProfile',params:{username : notification.user.username} }">
                                    @{{notification.user.username}}
                                    </router-link> <p> starting following you</p></span>
                            </li>
                        </template>
                    <span v-if="notifications.length>3 && !loadingNoti && !noMoreNoti" class="loadingMore">load More</span>
                    <span v-if="loadingNoti" class="loadingMore">
                        <div  id="spinner-grow" class="spinner-border spinner-border-sm" role="status" >
                                      <span class="sr-only" >Loading...</span>
                        </div>
                    </span>
                    <span v-if="noMoreNoti" class="loadingMore">No more notifications</span>
                    </ul>

                </div>
            
            </div>
            <div class="userBanaleTabs">
                <router-link :to="{name: 'profile'}">
                    <button  type="button" class="btn btn-primary authButton" data-toggle="modal" data-target="#exampleModalCenter">
                        <img class="userAuthButtonsImg" v-if="user.img" :src="user.img" >
                        <img v-else class="userAuthButtonsImg" src="/storage/users/user.png">
                        <p>{{user.name}}</p>
                    </button>
                </router-link>
                <router-link to="/dashboard">
                    <button   type="button" class="btn btn-primary authButton">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </button>
                </router-link>
                <button type="button" class="btn btn-primary authButton" data-toggle="modal" @click="logOut">
                    <i class="fas fa-door-open"></i>
                    <p>logout</p>
                </button>
            </div>
        </div>
  </div>
  <div class="feedbackDiv">
    <i class="toggleShowFeedback fas fa-certificate"></i>
    <div class="feedbackContainer">
        <span>Feedback</span>
        <div class="feedbacks">
        <div @click="sendFeedback(feedback.type)" v-for="feedback in feedbacks" :key="feedback.type" class="feedback">
            <img :src="feedback.icon" :alt="feedback.title">
            <span class="feedbackTitle">{{feedback.title}}</span>
        </div>
        </div>
    </div>
  </div>
  </div>
</template>

<script>
import forms from './formsComponent';

export default {
    data(){
        return{
            noMoreMessages:false,
            loadingMessages:false,
            noMoreNoti:false,
            loadingNoti:false,
            participantTypingState:false,
            participantSeenState:false,
            userTypingState:false,
            loadingChat:false,
            chatList: [],
            currentChat:{'id':null},
            message:new Form({
                destinationID : null,
                message : '',
                chatID: null,
            }),
            WholeChatNotifications:null,
            chatMessages:null,
            notifications:[],
            chatNotifications : [],
            feedbacks:[
                {'type':'satisfied',
                 'title':'Pleased',
                 'icon' : '/storage/feedback/pleased.svg'
                },
                {'type':'accepted',
                 'title':'Accepted',
                 'icon' : '/storage/feedback/huh.svg'
                },
                {'type':'ignored',
                 'title':'Ignored',
                 'icon' : '/storage/feedback/ignored.svg'
                },
            ],
            feedbackForm: new Form({
                type : "",
                message : ""
            }),
            notify:false,
            notificationCount:0,
            notifications:[],
            notificationTypes: {'liked':'App\\Notifications\\projectLiked',
                                'followed':'App\\Notifications\\followed',
                                'message':'App\\Notifications\\chatMessage',
                                'typing' : 'App\\Notifications\\typingState',
                                'seen' : 'App\\Notifications\\seenState',
                                },
        }
    },
    components:{
        forms
    },
  methods:{ 

        closeChat(){
          this.currentChat={'id':null}
          this.chatMessages = null;
        },

        getSpecificChat(noti){
            this.getChat({'id':noti.message.chat_id},null);
        },

        getChat(chat,userID){
            this.WholeChatNotifications = null
            this.loadingChat = true;
            this.message.reset();
            this.currentChat={'id':null}
            let chat_id = chat? chat.id : 0;
            userID = userID? userID : 0;
            this.chatMessages = null;
            this.participantTypingState=false;
            this.userTypingState= false;
            axios.get('/fetch/'+chat_id+'/'+userID).then((data)=>{
                this.currentChat = data.data.chat;
                this.chatMessages = data.data.messages;
                this.participantSeenState = this.chatMessages[this.chatMessages.length - 1].destinationState == 1
                this.chatNotifications = this.chatNotifications.filter((e)=>{
                    e.id == this.currentChat.id;
                });
            this.loadingChat = false;
                
            })
        },

        loadMoreMsgs(){
                if(!this.noMoreMessages){
                     this.loadingMessages = true;
                    axios.get('/fetchMoreMessages/'+this.currentChat.id+'/'+this.chatMessages.length).then((data)=>{
                        if(data.data.length == 0){
                            this.noMoreMessages = true;
                        }else{
                        document.getElementById('chatMessages').scrollBy(0,1);
                            
                            data.data.forEach((m)=>{
                                this.chatMessages.unshift(m);
                            });
                            this.noMoreMessages = false;
                        }
                     this.loadingMessages = false;
                    })
                }
        },
        loadMoreNoti(){
            let chatList = document.getElementById('notificationList');
            let nearToBottom = chatList.scrollTop == (chatList.scrollHeight - chatList.clientHeight);
            if(!this.noMoreNoti && nearToBottom && !this.loadingNoti){
                this.loadingNoti = true;
                axios.get('/fetchMoreNotifi/'+this.notifications.length).then((data)=>{
                    if(data.data.length == 0){
                        this.noMoreNoti = true;
                    }else{
                        data.data.forEach((n)=>{
                            this.notifications.push(n);
                        });
                        this.noMoreNoti = false;
                    }
                    this.loadingNoti = false;
                })
            }
        },
        updateTypingSate(e){
            if ((e.which <= 90 && e.which >= 48) || (e.which == 8 && this.message.message == '')) {
                let old = this.userTypingState;
                this.userTypingState = this.message.message != '';
                if(old != this.userTypingState){
                        axios.get('/typingState/'+this.currentChat.id);
                }
            }
            
        },

        sendMessage(){
            this.userTypingState = false;
            this.message.chatID = parseInt(this.currentChat.id) == this.currentChat.user[0].id? null :this.currentChat.id;
            this.message.destinationID = this.currentChat.user[0].id;
            this.message.post('/sendMessage').then((data)=>{
                    this.chatMessages.push(data.data);
                    this.message.message = '';
                    this.participantSeenState = false;
                }).catch(()=>{
                    alert('error');
                });

        },
      
        logOut(){
            axios.get('/logOut').then(({data})=>{
                this.user = data;
                this.$router.push({name:'home'});
                EventBus.$emit('updateLogInImg')
            });
        },
     
        navigateToAddProject(){
            if(this.$route.name != "dashboardProjects"){
            this.$router.push({name:'dashboardProjects'});
            }
        },

        animate(){
            setTimeout(() => {
                    var width = $('.authButtons').width() ;
                    $('.authButtons').css({'right':'-143px' });
            }, 2000);
        },

        async sendFeedback(type){
            this.feedbackForm.type = type
            const { value: text } = await Swal.fire({
            input: 'textarea',
            inputPlaceholder: 'thanks for your attention, More improvments?',
            inputAttributes: {
                'aria-label': 'Type your message here'
            },
            showCancelButton: true
            })
            if (text) {
            this.feedbackForm.message = text
            this.feedbackForm.post('/feedback')
            .then(({ data }) => { 
                if(data)
                {
                    Toast.fire({
                    icon: 'success',
                    title: 'Feedback Sent Successfully!'
                    }) 
                }
                })
            .catch(()=>{
                    Toast.fire({
                    icon: 'error',
                    title: 'Some thing went wrong!'
                    }) 
            });
            }
            console.log(this.feedbackForm);
        },
        
        toggleShowNoti(){
            if(this.notificationCount>0){
                axios.get('markNotiAsRead');
            }
            this.notificationCount = 0;

        },

  },
 async mounted(){
      this.animate();
    //  this.loadMoreNoti = _.debounce(this.loadMoreNoti, 1000)
    await this.$root.currentUser();
    if(this.user.id){
    this.notifications =  this.user.notifications;
    this.chatList = this.user.chat;
    this.chatNotifications = this.user.chatNotifications;
    this.notificationCount = this.notifications.filter(e=> e.read_at == null).length;
    window.Echo.private('App.User.' + this.user.id)
        .notification((notification) => {
            switch (notification.type) {
                case this.notificationTypes.message:
                    this.participantTypingState = false;
                    if(notification.message.chat_id == this.currentChat.id){
                    this.chatMessages.push(notification.message);
                    axios.get('/seenState/'+notification.message.chat_id);
                    }else{
                        if(!(this.chatList.findIndex(e=>e.id = notification.message.chat_id) >= 0)){
                            chatList.unshift({'id':notification.message.chat_id,
                                            'user':[notification.sender]});
                        }
                        this.chatNotifications.unshift(notification.message.chat_id);
                    }
                    if(!this.currentChat.id){
                    this.WholeChatNotifications= notification;
                    setTimeout(()=>{
                        this.WholeChatNotifications = null
                        },2000);
                    }
                    break;
                case this.notificationTypes.typing:
                    if(this.currentChat.id == notification.chat_id){
                        this.participantTypingState = !this.participantTypingState;
                    }
                    break;
                case this.notificationTypes.seen:
                    if(this.currentChat.id == notification.chat_id){
                        this.participantSeenState = true;
                        this.chatMessages[this.chatMessages.findIndex(e=>e.id == notification.message_id)].destinationState = 1;
                    }
                    break;
                default:
                    this.notificationCount++;
                    this.notifications.unshift(notification);
                    this.notify = true;
                    setTimeout(()=>{
                    this.notify = false;
                    },4000);
                    break;
            }
        });
    // window.Echo.private('chat'+this.user.id)
    //        .listen('ChatMessage', (e) => {
    //            console.log(e.message);
    //    });
    }},
  created(){
      window.Echo.join(`chat`)
    .here((users) => {
        //
    })
    .joining((user) => {
        console.log(user.name);
    })
    .leaving((user) => {
        console.log(user.name);
    });

  }
}
</script>

<style>
.authButtons{
  display: flex;
  align-items: center;
  justify-content: center;
  top: 29%;
  position: fixed;
  right: 3px;
  transition: all .3s linear;
  z-index: 100;
  
}

.authButton{
  display: flex;
  align-items: center;
  border-radius: 3px;
  margin-left: 5px;
  margin-bottom: 2.5px;
  width: 140px;
  justify-content: center;
  padding-top: 2px;
  padding-bottom: 2px;
}
.authButton p{
  margin-bottom: 0px;
  font-weight: 600;
  margin-left: 3px;
  width: 90%;
}
 .authButtons .userColumnsbanale{
     display:flex;margin-left: 7px;
 }
 .authButtons .router-link-active{
     background: transparent!important;
     text-decoration: none;
 }
 .authButtons .router-link-active button{
         background: #2734c9!important;
    border-color: transparent;
 }
 .authButtons .userColumnsbanale .userBanaleTabs{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    align-content: center;
 }
.authButtons .userColumnsbanale .userBanaleIcons{
     display: flex;
     flex-direction: column;
     position: relative;
 }
.authButtons  .before{
  background: white;
  cursor: pointer;
  display: flex;
  cursor: pointer;
  justify-content: center;
  align-items: center;
  width: 30px;
  height: 30px;
  color: black;
  left: 0px ;
  font-size: 20px;
  border-radius: 32px;
  margin: 1px 0px;
}
 .authButtons a{
     text-decoration: none;
 }
 .userAuthButtonsImg{
     width:25px;height:24px;border-radius: 30px;
 }

 .newProject{
         animation: newProjectAnimation 3s ease-in-out infinite 0s!important;
         transition: all .3s linear;
         color: white;
 }
 .feedbackDiv{
    z-index: 9999;
    position: fixed;
    top: 11%;
    right: -137px;
    transition: all .2s linear;
    display: flex;
    flex-direction: row;
    align-items: center;
 }
 .showFeedback{
     right: 3px!important;
 }
 .feedbackDiv i{
    margin-right: 10px;
    font-size: 25px;
    color: #5bbc2e;
    cursor: pointer;
 }
 .feedbackContainer{
    display: flex;
    flex-direction: column;
    background: white;
    border-radius: 5px;
 }
 .feedbackDiv .feedbacks{
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 8px;
 }
 .feedbackContainer span{
    text-align: center;
    font-weight: 600;
    color: #494949;
    border-bottom: 1px solid #d2d2d2;
 }
 .feedback{
    padding: 0px 5px;
    cursor: pointer;
    position: relative;
 } 
 .feedback:hover .feedbackTitle{
    display: flex;
    width: auto;
 }
 .feedbackContainer img{
    width: 30px;
    height: 30px;
 }
  .feedbackTitle{
    display: none;
    color: white!important;
    font-weight: 500;
    justify-content: center;
    border-radius: 3px;
    position: absolute;
    top: 35px;
    background: black;
    left: -10px;
    font-size: 11px;
    padding: 2px 3px;
    min-width: 60px;
 }
 .swal2-actions button{
  padding :2px 11px!important;
 }
  .notifications{
    background: #000000c4!important;
    color: #ffbf02!important;
    position: relative;
 }
 .reddot, .firstChat::before{
    content: '';
    font-size: 11px;
    background: red;
    color: white;
    position: absolute;
    top: 0px;
    left: 0px;
    width: 10px;
    height: 10px;
    border-radius: 15px;
 }
 .currentChat{
    border: 2px solid #007bff !important;
    padding: 20px;
 }
 .notificationCenter{
    position: absolute;
    width:310px;
    max-width:310px;
    display: none;
    flex-direction: column;
    background: white;
    padding: 0px;
    border-radius: 5px;
    left: -318px;
    bottom: -162px;
    height: 200px;
    box-shadow: 0 0 20px #0000007a;
 }
 .notificationList{
    padding: 0px 10px;
    overflow: auto;
    margin: 0;
 }
 .notificationList li{
    display: flex;
    align-items: center;
    padding: 5px;
    margin: 6px 0px;
    transition: all .1s linear;
    border-radius: 4px;
    box-shadow: 0 0 8px #00000052;
 }
 .notificationList .loadingMore{
     padding: 0px 1px 6px 1px
 }
 .notificationList  .router-link-active{
     color: #007bff!important;
     background: white!important;
 }
 .notificationList li:hover{
     cursor: pointer;
    box-shadow: 0 0 8px #0000009f;
 }
 .notificationList li img{
     width: 20px;
    height: 20px;
    object-fit: scale-down;
 }
 .notificationList li span{
        display: flex;
        font-weight: 500;
        margin-left: 3px;
        font-size: 13px;
 }
 .notificationList span p{
     width: max-content;
     margin: 0px 0px 0px 2px;
 }
 .chatIcon:hover {
     font-size: 26px;
 }
 .chatIcon i{
     transition: all .1s linear;
     cursor: pointer;
 }
 .chatIcon{
    width: 50px;
    height: 50px;
    position: fixed;
    bottom: 11px;
    right: 10px;
    padding: 10px 8px 8px 8px;
    background: #5bbc2e;
    border-radius: 80px;
    color: white;
    z-index: 101;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 6px 5px 20px #0000008a;
    font-size: 24px;
 }
 .chatBox{
    z-index: 100;
    position: fixed;
    bottom: 11px;
    display: none;
    right: 54px;
    width: 300px;
    height: 400px;
    background: white;
    overflow: auto;
    border-radius: 6px;
    box-shadow: 6px 5px 20px #0000008a;
 }
 .followingChats{
    height: 100%;
    max-height: 450px;
    display: flex;
    flex-direction: column;
    align-items: center;
    border-right: 2px solid #5bbc2e;
    padding: 5px 2px;
 }
 .chatBody{
     height: 100%;
     position: relative;
     display: flex;
     flex-direction: column;
     overflow: auto;
     max-height: 450px;
 }
.chatBody .chatHeader{
    padding: 5px 7px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 12%;
    background: #28a745;
    color: white;
    font-weight: 400;
    box-shadow: 0px 1px 20px #0000009e;
    text-transform: capitalize;
}
.chatHeader i{
    cursor: pointer;
}
.chatHeader img{
    width: 30px;
    height: 30px;
    margin-right: 5px;
    border-radius: 40px;
}
 .chatMessages{
    min-height: 312px;
    overflow: auto;
    display: flex;
    flex-direction: column;
    padding: 5px;
    top: 0px;
    width: 100%;
 }
 
 .chatMessages li{
     width:100%;
     list-style: none;
     margin:3px 0px;
     max-width: 100%;
     display: flex;
     flex-direction: column;
 }

 .chatMessages li span:first-child{
    max-width: 100%;
    width: fit-content;
    background: #007bff;
    color: white;
    padding: 4px;
    border-radius: 9px;
    cursor: pointer;
 }
  .chatMessages li span:nth-child(2){
    display: none;
    padding: 2px;
    border-radius: 2px;
    font-size: 10px;
    background: transparent;
    width: fit-content;
    color: gray;
    font-weight: 500;
 }
 .chatMessages .floatRight{
         direction: rtl;
 }
  .chatMessages .floatRight span:first-child{
     float:right;
     background: #3bae13;
 }
  
 .chatState{
    background: transparent;
    color: rgba(0,0,0,.5);
    font-size: 12px;
    padding: 0px 9px;
}
.chatState p{
    width:fit-content;direction:ltr
}



 .chatBody form{
    padding: 0px;
 }
 .chatBody form input{
    min-height: 40px;
    width: 96%;
    width: 100%;
    border: none;
    padding: 2px 10px;
    border-top: 1px solid #cbc7c7;
    border-top-right-radius: 2px;
    border-top-left-radius: 2px;
    color: #000000cf;
    transition: all .1s linear;
 }
 .chatBody form input:focus{
     outline: none;
    border-top: 2px solid #5bbc2e;
 }
 .followingChats li{
    list-style: none;
    margin: 5px;
    border: 1px solid #5bbc2e;
    border-radius: 30px;
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    cursor: pointer;
    transition: all 0.2s linear;
 }

 .followingChats li img{
    width: 35px;
    height: 35px;
    border-radius: 30px;
 }
 .followingChats li span{
     background: black;
     color: white;
     padding: 3px;
     border-radius: 3px;
     position: absolute;
     left: 42px;
     display: none;
     font-size: 10px;
     z-index: 150;
 }
 .followingChats li:hover span{
     display: flex;
 }
.followingChats hr{
    margin: 0px;
    border: 0;
    border-top: 1px solid rgba(0,0,0,.5);
    width: 100%;
}

.chooseChat{
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
.chooseChat span {
    font-weight: lighter;
    text-align: center;
}
.chatBody .router-link-active{
    background: transparent!important;
}
.loadingChat{
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.realTimeChatNoti{
    cursor: pointer;
    width: 50px;
    height: 50px;
    list-style: none;
    border-radius:50px ;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: row-reverse;
    position: relative;
}
.realTimeChatNoti img{
    width: 50px;
    height: 50px;
    border-radius:50px ;
}
.realTimeChatNoti span{
    color: red;
    background: black;
    padding: 0 5px;
    margin-right: 4px;
    border-radius: 3px 2.9px 3px 3px;
    font-size: 16px;
    position: absolute;
    right: 60px;
    font-weight: 500;
    height: 25px;
}
.realTimeChatNoti span::before{
    content: "\A";
    border-style: solid;
    border-width: 11px 0px 14px 15px;
    border-color: transparent transparent transparent #000;
    position: absolute;
    right: -14px;
    display: flex;
    top: 0px;
}
.loadingMore{
    display: flex;
    justify-content: center;
    flex-direction: row;
    color: gray;
    padding-top: 0px;
    font-weight: 600;
    font-size: 12px;
}
.tiblock {
    align-items: center;
    display: flex;
    height: 17px;
    padding-left: 6px;
}

.ticontainer .tidot {
    background-color: #90949c;
}

.tidot {
    -webkit-animation: mercuryTypingAnimation 1.5s infinite ease-in-out;
    border-radius: 2px;
    display: inline-block;
    height: 4px;
    margin-right: 2px;
    width: 4px;
}

@-webkit-keyframes mercuryTypingAnimation{
0%{
  -webkit-transform:translateY(0px)
}
28%{
  -webkit-transform:translateY(-5px)
}
44%{
  -webkit-transform:translateY(0px)
}
}

.tidot:nth-child(1){
-webkit-animation-delay:200ms;
}
.tidot:nth-child(2){
-webkit-animation-delay:300ms;
}
.tidot:nth-child(3){
-webkit-animation-delay:400ms;
}
    </style>