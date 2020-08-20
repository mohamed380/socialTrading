<template>
        <button v-if="user.id!=null && user.id!=id" @click.stop="follow" class="btn btn-sm " :class="{'btn-outline-primary':!following,'btn-primary':following}" style="display:flex;align-items:center"><i  @click="follow" class="fas" :class="{'fa-plus':following==false,'fa-check':following==true,'fa-cog':following == null}" :disabled="following == null" style="margin-right:4px"></i> <p v-if="following">Following</p><p v-else>Follow</p></button>
</template>

<script>
export default {
    data(){
        return{
            following :false,
            id: null,
            requestedUser:null
        }
    },
    methods:{
        async follow(){
            let old = this.following;
               this.following = null;
                if(this.user.id != null && this.id){
                       await axios.get('/follow/'+this.id).then(({data})=>{
                            this.following = data;        
                            if(this.following){
                                this.user.following.push(this.requestedUser);
                            }else{
                                this.user.following = this.user.following.filter((e) => {e.id != this.requestedUser.id});
                            }
                        })
                        .catch(({data})=>{
                        this.following = old;
                        });
                }else{
                this.following = old;
                    alert('sign Up!');
                }
        },
        followingState(user){
            this.id = user.id;
            this.requestedUser = user;
            this.following = false;
            if(this.user.id){
            if(this.user.following.length>0){
                this.following = this.user.following.findIndex((e) => e.id == user.id)>=0; //2lwaza to handle zero index
                return true;
            }
            this.following = false;
            return true;
            }
            this.following = false;
            return true;
        }
    },
    mounted(){
       EventBus.$on('followingState', (user) => 
        {
            if(user != null)
            {
                this.followingState(user);
            }
        }
        );
    },
    created(){

    }
}
</script>

<style>
p{
    margin: 0px;
}
</style>