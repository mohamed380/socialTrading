<template>
  <div class="container-fluid nav-top" :class="{'topSearchBox':$route.name != 'home' && $route.matched[0].name!='dashboard','homeSearchBox':$route.name == 'home','hideSearchBox':$route.matched[0].name=='dashboard'}" style="z-index:99">
            <div class="searchBox row">
                <form action="" id="searchForm" class="col-12" style="padding:0px">
                    <div class="bb" style="position:relative;">
                        <input type="text" @keyup="searchByToken" class="form-control searchInput" name="searchToken" id="" placeholder="Search" v-model="searchTitle">
                        <div class="searchIcons">
                            <i v-if="!searchTitle" class="fas fa-search"></i>
                            <div style="margin-right:13px" v-else-if="searchTitle && !searchList" class="spinner-border text-secondary spinner-border-sm" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <i style="postition:relative" class="filter fas fa-ellipsis-v"></i><span v-if="query.programs.length+query.categories.length+query.subcategories.length > 0"  class="filterCount">{{query.programs.length+query.categories.length+query.subcategories.length}}</span>
                        </div>
                        <ul v-if="searchList" class="searchList">
                            <template v-if="searchList.length>0">
                                <router-link v-for="item in searchList" :key="item.id" :to="{name:'project', params:{id: item.id,title: item.title.replace(/\s+/g, '-')}}">
                                    <li >
                                        {{ item.title }}
                                    </li>
                                </router-link>
                            </template>
                            <template v-else>
                                  <li style="background:#a1a3a5">
                                       <p>No Matchs</p>
                                    </li>
                            </template>
                        </ul>
                    </div>
                    <div class="searchOptionsBox row" style="margin:0px;padding: 3px 0px;display:none">
                        <div  class="customDropdownList col-xl-4 col-lg-4 col-md-12 col-sm-12">
                             <span class="optionsTitle">
                                <p>Categories</p>
                                <span v-if="query.categories.length>0 " style="background:#ee8977" class="optionsCount">{{query.categories.length}}</span>
                                <i class="moreOptions fas fa-chevron-circle-down"></i>
                             </span>
                            <div class="dropList" >
                                <div v-if="!categories.length" style="display:flex;flex-flow: wrap;">
                                        <span class="loading option"></span>
                                        <span class="loading option" ></span>
                                        <span class="loading option" ></span>
                                        <span class="loading option" ></span>
                                    </div>
                                <li  v-for="category in categories" :key="category.id" @click="searchByCategory(category.title)" class="option"  v-bind:class="{'activeSearchOptions':query.categories.includes(category.title) }" >
                                        <input type="checkbox" class="optionCheckbox" :checked="query.categories.includes(category.title)">
                                        <img :src="'/'+category.img"/>
                                        <p>{{category.title}}</p>
                                </li>    
                            </div>
                        </div>
                        <div  class="customDropdownList col-xl-4 col-lg-4 col-md-12 col-sm-12">
                            <span class="optionsTitle">
                                <p>Subcategories</p>
                                <span v-if="query.subcategories.length > 0" style="background:#a3e985" class="optionsCount">{{query.subcategories.length}}</span>
                                <i class="moreOptions fas fa-chevron-circle-down"></i>
                             </span>
                            <div class="dropList">
                                    <div v-if="!subCategrories.length" style="padding:3px;0px">
                                        <span class="loading option"></span>
                                        <span class="loading option" ></span>
                                        <span class="loading option" ></span>
                                        <span class="loading option" ></span>
                                    </div>
                                <li v-for="subcategory in subCategrories" :key="subcategory.id" @click="searchBySubCategory(subcategory.title)" class="option"  v-bind:class="{'activeSearchOptions':query.subcategories.includes(subcategory.title) }">
                                    <input type="checkbox" class="optionCheckbox" :checked="query.subcategories.includes(subcategory.title)">
                                    <p>{{subcategory.title}}</p>
                                </li>    
                            </div>
                         </div>
                         <div  class="customDropdownList col-xl-4 col-lg-4 col-md-12 col-sm-12">
                            <span class="optionsTitle">
                                <p>Programs</p>
                                <span v-if="query.programs.length > 0" style="background:#a3e985" class="optionsCount">{{query.programs.length}}</span>
                                <i class="moreOptions fas fa-chevron-circle-down"></i>
                             </span>
                            <div class="dropList" >
                                    <div v-if="!programs.length" style="padding:3px;0px">
                                        <span class="loading option"></span>
                                        <span class="loading option" ></span>
                                        <span class="loading option" ></span>
                                        <span class="loading option" ></span>
                                    </div>
                                <li v-for="program in programs" :key="program.id" @click="searchByProgram(program.title)" class="option"  v-bind:class="{'activeSearchOptions':query.programs.includes(program.title) }">
                                    <input type="checkbox" class="optionCheckbox" :checked="query.programs.includes(program.title)">
                                    <p>{{program.title}}</p>
                                </li>    
                            </div>
                         </div>
                         

                        <div  class="searchOptionsBoxRow col-12" style="padding:0px 6px">
                                <div class="colorSearchOptions">
                                    <div v-if="!tags.length" style="display:flex;flex-flow: wrap;">
                                        <span class="loading " ></span>
                                        <span class="loading " ></span>
                                        <span class="loading " ></span>
                                        <span class="loading " ></span>
                                    </div>
                                    <li style=" background: #5ab9ed;" v-else v-for="tag in tags" :key="tag.id" @click="searchByTag(tag.title)"  class="colorOption">
                                        <p>{{tag.title}}</p>
                                        <i    class="fas fa-plus" :class="{'checked':query.tags.includes(tag.title)}"></i>
                                    </li>    
                                </div>
                        </div>
                    
                    </div>
                </form>
            </div>
  <div class="trickDiv"></div>

        </div>
</template>

<script>
export default {
    data(){
     return{
            categories : [],
            subCategrories : [],
            programs : [],
            tags: [],
            query:{
                categories:[],
                subcategories:[],
                programs:[],
                type:'any',
                tags:[],
             },
             searchTitle:'',
             searchList: null,
        }
    },
    methods:{
        async initializer()
        {
            if(this.$router['currentRoute']['query']){
            let routQuery = this.$router['currentRoute']['query'];
            this.query['categories'] = (this.query['categories'].concat(routQuery['categories'])).filter(function(element){
                return element !== undefined;
            });
            this.query['subcategories'] = (this.query['subcategories'].concat(routQuery['subcategories'])).filter(function(element){
                return element !== undefined;
            });
            this.query['programs'] = (this.query['programs'].concat(routQuery['programs'])).filter(function(element){
                return element !== undefined;
            });
            this.query['tags'] = (this.query['tags'].concat(routQuery['tags'])).filter(function(element){
                return element !== undefined;
            });
            this.query['type'] = routQuery['type']?routQuery['type']:'any';
            }
            if(this.$root.categories.length!=0){
                this.categories = this.$root.categories;
                this.subCategrories = this.$root.subCategrories;
                this.programs = this.$root.programs;
                this.tags = this.$root.tags;
            }else{
            await  axios.get('/home').then( ({ data }) => {
                this.categories = data.categories;
                this.subCategrories = data.subCategrories;
                this.programs = data.programs;
                this.tags = data.tags;
            });
            this.$root.categories=this.categories;
            this.$root.subCategrories=this.subCategrories;
            this.$root.programs=this.programs;
            this.$root.tags=this.tags;
            }

        },
      
        editSearchQuery(key,title){
            if(this.query[key].includes(title)){
                this.query[key] = this.query[key].filter(val => val != title);
            }else{
                this.query[key].push(title);
            }
            if(this.$route.path == '/searchProjects'){
                this.$router.replace({query: this.query});
            }else{
                this.$router.push({name:'searchProjects',query: this.query});
            }
            this.$root.loadSearch = false;
            EventBus.$emit('searchByQuery', this.query);
        },

        searchByCategory(category){
            this.editSearchQuery('categories',category);
        },

        searchBySubCategory(subcategory){
            this.editSearchQuery('subcategories',subcategory);
        },

        searchByProgram(program){
            this.editSearchQuery('programs',program);             
        },

        searchByTag(tag){
            this.editSearchQuery('tags',tag);             
        },

        searchBytype(type){
        },

        searchByToken(){
            this.searchList = null;
            if(this.searchTitle){
                axios.get('/directSearch/'+this.searchTitle).then(({data})=>{
                    this.searchList = data;
                    return true;
                }).catch(()=>{
                        return false;
                });
            }
        }
    },
    mounted(){
        EventBus.$on('searchByTag', (tag) => 
        {
            if(tag != null)
            {
                this.searchByTag(tag);
            }
        });
           EventBus.$on('searchByCategory', (title) => 
        {
            if(title != null)
            {
                this.searchByCategory(title);
            }
        })
       this.searchByToken =  _.debounce(this.searchByToken, 500);
       this.initializer();
    },
 
}
</script>

<style>
.checked{
    transform: rotate(45deg);
    color: #ef7777!important;
}
    .activeSearchOptions{
        background: wheat;
    }
    .searchOptionsBoxRow .optionsTitle{
        border: 0;
        margin: 0px;
    }
    .colorSearchOptions{
        display: flex;
        flex-flow: wrap;
        align-items: center;
        padding: 5px 14px;
    }
    .colorSearchOptions div span{
        width: 90px;height: 24px;display: block;border-radius: 20px;margin:2px 2px;
    }
    .colorOption{
        list-style: none;
        padding: 0px 8px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        height: 20px;
        cursor: pointer;
        margin:2px 2px;
        position: relative;
    }
   .colorOption p{
        margin: 0px;
        font-weight: 600;
        color: white;
  font-size: 13px;
    }
    .colorOption i{
      color: white;
      margin-left: 7px;
      
      transition: all .2s linear;
    }
    .filterCount{
        background: rgb(249, 97, 97);
        top: -9px;
        right: 3px;
        width: 10px;
        height: 10px;
        font-size: 10px;
        padding: 6px;
        color: white;
        border-radius: 10px;
        position: absolute;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .searchList{
        list-style: none;
        padding: 3px;
        margin: 0px;
    }
    .searchList a{
        text-decoration: none;
    }
    .searchList li{
        padding: 3px 12px;
        background: #5d6871;
        color: #ffffff;
        font-weight: 500;
        border-radius: 4px;
        margin: 2px 0px;
        transition: all .2s linear;
    }
    .searchList li:hover{
        background: #78848e;
    }
    .trickDiv{
        position: fixed;
        width: 100%;
        height: 100%;
        z-index: 0;
        top: 54px;
        left: 0px;
        background: rgba(0, 0, 0, 0.46);
        display: none;
    }
</style>