
export function getProjects(len)
{
    let result =[];
    let pr = [];
    let l  = [];
    axios.get('/getProjects/'+len).then( ({ data }) => {
      data.likes.forEach(like=>{
           l.push(like)
      });
      data.projects.forEach(p=>{ 
        pr.push(p);
      });
    });
    result.push(pr);
    result.push(l);
    return result;
}

export async function explore(length)
{
  var projects = [];
  await axios.get('/exploreProjects/'+length).then( ({ data }) => {
        data.forEach((p)=>{
          projects.push(p);
        });
        });
        console.log(projects);
  return projects;
}

export async function followingProjects(length)
{
  var projects = [];
  await axios.get('/followingProjects/'+length).then( ({ data }) => {
        data.forEach((p)=>{
          projects.push(p);
        });
        });
  return projects;
}

export async function MoreProjects(len)
{
  let pr = [];
  await axios.get('/getProjects/'+len).then( ({ data }) => {
      pr = data.projects;
    });
  return pr;
}

export async function searchProjects(searchQuery,length)
{
      var projects=[];
      await axios.get('/search/'+JSON.stringify(searchQuery)+'/'+length).then(({data})=>{  
        data.forEach((p)=>{
          projects.push(p);
        });
      });
     return projects;
}

export async function getProject(id)
{
  var project = {};
  var result = {
    'project':null,
    'like':null
  }
  var relatedProjects = [];
  var like = false;
  await axios.get('/getProject/'+id).then( ({ data }) => {
        project = data.project;
        like = data.like;
        }).catch(()=>{
          project = null;
          like = null;
        });
  result.project = project;
  result.like = like;
  return result;
}

export async function getRelatedProjects(id,length)
{
  var relatedProjects = [];
  await axios.get('/getRelatedProjects/'+id+'/'+length).then(({ data }) => {
    if(data){
        data.forEach((p)=>{
          relatedProjects.push(p);
        });
    }else{
      relatedProjects = null;
    }
    }).catch(()=>{
      relatedProjects = null;
    });
    return relatedProjects;
}



