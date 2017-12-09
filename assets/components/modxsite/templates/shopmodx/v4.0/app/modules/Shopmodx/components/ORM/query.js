const defaultQuery = `

## Default
query EditorState {
  editorState{
    ...EditorState
  }
}

fragment EditorState on CommentEditorStateType{
  blocks{
    data
    depth
    entityRanges
    inlineStyleRanges
    key
    text
    type
  }
  entityMap{
    __typename
    
    ... on EditorEntityDefaultType{
      type
      mutability
      ...EditorEntity
    }
    
    ... on EditorEntityGalleryType{
      ...EditorEntityGallery
      type
      mutability
    }
    
    ... on EditorEntityLinkType{
      type
      data{
        target
        title
        url
        _map
      }
    }
    
    ... on EditorEntityImageType{
      type
      mutability
      data{
        src
      }
    }
    
  }
}

fragment EditorEntity on EditorEntityDefaultType{
  type
  mutability
  data{
    gallery{
      image
    }
    target
    title
    url
    _map
  }
}

fragment EditorEntityGallery on EditorEntityGalleryType{
  type
  mutability
  data{
    gallery{
      image
      imageFormats{
        thumb
        slider_thumb
        slider_dot_thumb
        middle
        big
      }
    }
  }
}


## Custom

query apiData(
  $modxResourcesLimit:Int = 0
  $modxResourcesPage:Int
  $withPagination:Boolean = false
  $modxResourcesContextKey:String
  $modxResourcesShowHidden:Boolean = true
  $modxResourcesShowUnpublished:Boolean = true
  $modxResourcesStorage:ReactCmsStorageStoreType = remote
  $modxResourcesSort:[MODXResourceSortBy]
  $modxResourcesParent:Int
  $apiGetResources:Boolean = true
  $modxResourcesTemplates:[Int]
  $modxResourcesOptions:JSON
  $modxResourcesUri:String
  $getImageFormats:Boolean = false
){
  
  ...RootMODXResources @include(if: $apiGetResources)
  
}

# Сброс серверного кеша
mutation clearCache{
  clearCache @storage(store:remote)
}




query CurrentUser(
  $getImageFormats:Boolean = true
)
{
  
  user(
    ownProfile: true
  ) @storage(store:remote)
  {
    ...User
  }
}


fragment User on UserType {
  ...UserFields
}

fragment UserFields on UserType{
  id
  username
  fullname
  email
  active
  sudo
  blocked
  createdon
  createdby
  delegate
  offer
  offer_date
  contract_date
  image
  imageFormats @include(if:$getImageFormats)
  {
    thumb
    small
    middle
    big
  }
}


query MainMenuData(
  $modxResourcesLimit:Int = 10
  $modxResourcesPage:Int
  $modxResourcesContextKey:String = "web"
  $modxResourcesShowHidden:Boolean = false
  $modxResourcesShowUnpublished:Boolean = false
  $modxResourcesStorage:ReactCmsStorageStoreType
  $modxResourcesSort:[MODXResourceSortBy] = {
    by: menuindex
    dir: asc
  }
  $modxResourcesParent:Int = 0
  $modxResourcesTemplates:[Int]
  $modxResourcesOptions:JSON
  $modxResourcesUri:String
  $getImageFormats:Boolean = false
)
{
  
  menuItems:modxResources(
    limit: $modxResourcesLimit
    page: $modxResourcesPage
    context_key: $modxResourcesContextKey
    showhidden:$modxResourcesShowHidden
    showunpublished:$modxResourcesShowUnpublished
    parent:$modxResourcesParent
    templates:$modxResourcesTemplates
    uri:$modxResourcesUri
    sort:$modxResourcesSort
    options:$modxResourcesOptions
  )
    @storage(store:$modxResourcesStorage)
  {
    id
    pagetitle
    longtitle
    menutitle
    hidemenu
    published
    deleted
    alias
    uri
    context_key
    image
    imageFormats @include(if:$getImageFormats)
    {
      thumb
      slider_thumb
      slider_dot_thumb
      small
      middle
      big
    }
  }
  
}


# Все документы
query MODXResources(
  $modxResourcesLimit:Int = 10
  $modxResourcesPage:Int
  $withPagination:Boolean = false
  $modxResourcesContextKey:String = "web"
  $modxResourcesShowHidden:Boolean
  $modxResourcesShowUnpublished:Boolean
  $modxResourcesStorage:ReactCmsStorageStoreType
  $modxResourcesSort:[MODXResourceSortBy]
  $modxResourcesParent:Int
  $modxResourcesTemplates:[Int]
  $modxResourcesOptions:JSON
  $modxResourcesUri:String
  $getImageFormats:Boolean = false
)
{
  
  ...RootMODXResources
  
}


# Все товары
query CatalogProducts(
  $modxResourcesLimit:Int = 10
  $modxResourcesPage:Int
  $withPagination:Boolean = false
  $modxResourcesContextKey:String = "web"
  $modxResourcesShowHidden:Boolean
  $modxResourcesShowUnpublished:Boolean
  $modxResourcesStorage:ReactCmsStorageStoreType
  $modxResourcesSort:[MODXResourceSortBy]
  $modxResourcesParent:Int
  $modxResourcesTemplates:[Int] = [3]
  $modxResourcesOptions:JSON
  $modxResourcesUri:String
  $getImageFormats:Boolean = false
)
{
  
  ...RootMODXResources
  
}


# Все категории
query CatalogCategories(
  $modxResourcesLimit:Int = 10
  $modxResourcesPage:Int
  $withPagination:Boolean = false
  $modxResourcesContextKey:String = "web"
  $modxResourcesShowHidden:Boolean
  $modxResourcesShowUnpublished:Boolean
  $modxResourcesStorage:ReactCmsStorageStoreType
  $modxResourcesSort:[MODXResourceSortBy]
  $modxResourcesParent:Int
  $modxResourcesTemplates:[Int] = [2]
  $modxResourcesOptions:JSON
  $modxResourcesUri:String
  $getImageFormats:Boolean = false
)
{
  
  ...RootMODXResources
  
}

query CatalogCategory(
  $categoryId:Int!
  $modxResourcesLimit:Int = 10
  $modxResourcesPage:Int
  $withPagination:Boolean = false
  $modxResourcesContextKey:String = "web"
  $modxResourcesShowHidden:Boolean
  $modxResourcesShowUnpublished:Boolean
  $modxResourcesStorage:ReactCmsStorageStoreType
  $modxResourcesSort:[MODXResourceSortBy]
  $modxResourcesCategoryTemplates:[Int] = [2]
  $modxResourcesProductTemplates:[Int] = [3]
  $modxResourcesOptions:JSON
  $modxResourcesUri:String
  $getImageFormats:Boolean = false
  $categoryGetSubCategories:Boolean = true
  $categoryGetProducts:Boolean = true
)
{
   
  ...CategorySubCategories @include(if:$categoryGetSubCategories)
  
  
  ...CategoryProducts @include(if:$categoryGetProducts)
  
}


fragment CategorySubCategories on RootType{
  # дочерние категории
  subcategoriesList: modxResourcesList(
    limit: $modxResourcesLimit
    page: $modxResourcesPage
    context_key: $modxResourcesContextKey
    showhidden:$modxResourcesShowHidden
    showunpublished:$modxResourcesShowUnpublished
    parent:$categoryId
    templates:$modxResourcesCategoryTemplates
    uri:$modxResourcesUri
    sort:$modxResourcesSort
    options:$modxResourcesOptions
  ) 
    @include(if:$withPagination)
    @storage(store:$modxResourcesStorage)
  {
    count
    total
    limit
    page
    object{
      ...MODXResource
    }
  }
  
  subcategories: modxResources(
    limit: $modxResourcesLimit
    page: $modxResourcesPage
    context_key: $modxResourcesContextKey
    showhidden:$modxResourcesShowHidden
    showunpublished:$modxResourcesShowUnpublished
    parent:$categoryId
    templates:$modxResourcesCategoryTemplates
    uri:$modxResourcesUri
    sort:$modxResourcesSort
    options:$modxResourcesOptions
  ) 
    @skip(if:$withPagination)
    @storage(store:$modxResourcesStorage)
  {
    ...MODXResource
  }
}

fragment CategoryProducts on RootType{
  
  #дочерние товары
  productsList: modxResourcesList(
    limit: $modxResourcesLimit
    page: $modxResourcesPage
    context_key: $modxResourcesContextKey
    showhidden:$modxResourcesShowHidden
    showunpublished:$modxResourcesShowUnpublished
    parent:$categoryId
    templates:$modxResourcesProductTemplates
    uri:$modxResourcesUri
    sort:$modxResourcesSort
    options:$modxResourcesOptions
  ) 
    @include(if:$withPagination)
    @storage(store:$modxResourcesStorage)
  {
    count
    total
    limit
    page
    object{
      ...MODXResource
    }
  }
  
  products: modxResources(
    limit: $modxResourcesLimit
    page: $modxResourcesPage
    context_key: $modxResourcesContextKey
    showhidden:$modxResourcesShowHidden
    showunpublished:$modxResourcesShowUnpublished
    parent:$categoryId
    templates:$modxResourcesProductTemplates
    uri:$modxResourcesUri
    sort:$modxResourcesSort
    options:$modxResourcesOptions
  ) 
    @skip(if:$withPagination)
    @storage(store:$modxResourcesStorage)
  {
    ...MODXResource
  }
}


query MODXResourceById(
  $modxResourceId:Int!
  $getImageFormats:Boolean = false
  $modxResourcesShowHidden:Boolean
  $modxResourcesShowUnpublished:Boolean
)
{
  
  modxResource(
    id:$modxResourceId
    showhidden:$modxResourcesShowHidden
    showunpublished:$modxResourcesShowUnpublished
  ){
    ...MODXResource
  }
  
}

query MODXResourceByUri(
  $modxResourceUri:String!
  $getImageFormats:Boolean = false
  $modxResourcesShowHidden:Boolean
  $modxResourcesShowUnpublished:Boolean
)
{
  
  modxResource(
    uri:$modxResourceUri
    showhidden:$modxResourcesShowHidden
    showunpublished:$modxResourcesShowUnpublished
  ){
    ...MODXResource
  }
  
}

fragment RootMODXResources on RootType{
  
  modxResourcesList(
    limit: $modxResourcesLimit
    page: $modxResourcesPage
    context_key: $modxResourcesContextKey
    showhidden:$modxResourcesShowHidden
    showunpublished:$modxResourcesShowUnpublished
    parent:$modxResourcesParent
    templates:$modxResourcesTemplates
    uri:$modxResourcesUri
    sort:$modxResourcesSort
    options:$modxResourcesOptions
  ) 
    @include(if:$withPagination)
    @storage(store:$modxResourcesStorage)
  {
    count
    total
    limit
    page
    object{
      ...MODXResource
    }
  }
  
  modxResources(
    limit: $modxResourcesLimit
    page: $modxResourcesPage
    context_key: $modxResourcesContextKey
    showhidden:$modxResourcesShowHidden
    showunpublished:$modxResourcesShowUnpublished
    parent:$modxResourcesParent
    templates:$modxResourcesTemplates
    uri:$modxResourcesUri
    sort:$modxResourcesSort
    options:$modxResourcesOptions
  ) 
    @skip(if:$withPagination)
    @storage(store:$modxResourcesStorage)
  {
    ...MODXResource
  }
  
}

fragment MODXResource on MODXResourceType{
  
  ...MODXResourceFields
  
}

fragment MODXResourceFields on MODXResourceType{
  
  id
  pagetitle
  longtitle
  description
  alias
  link_attributes
  parent
  template
  menuindex
  menutitle
  content
  isfolder
  published
  createdby
  createdon
  publishedon
  publishedby
  pub_date
  unpub_date
  deleted
  deletedon
  deletedby
  editedon
  editedby
  hidemenu
  class_key
  context_key
  content_type
  richtext
  uri
  uri_override
  hide_children_in_tree
  show_in_tree
  price
  price_old
  article
  image
  imageFormats @include(if:$getImageFormats)
  {
    thumb
    slider_thumb
    slider_dot_thumb
    small
    middle
    big
  }
  properties
  tvs
  _other
  
}

`;

export default defaultQuery;
