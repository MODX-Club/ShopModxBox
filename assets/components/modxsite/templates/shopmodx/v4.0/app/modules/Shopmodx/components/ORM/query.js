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
  subCategoriesList: modxResourcesList(
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
  
  subCategories: modxResources(
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




query Orders(
  $ordersLimit:Int = 10
  $withPagination:Boolean = false
  $orderGetProducts:Boolean = false
  $orderProductGetProduct:Boolean = false
  $getImageFormats:Boolean = false
){
  
  ...RootOrders
  
}

fragment RootOrders on RootType{
  
  ordersList(
    limit:$ordersLimit
  ) 
  @include(if:$withPagination)
  @storage(store:remote)
  {
    count
    total
    limit
    page
    object{
      ...Order
    }
  }
  
  orders(
    limit:$ordersLimit
  ) 
  @skip(if:$withPagination)
  @storage(store:remote)
  {
    ...Order
  }
  
}

fragment Order on OrderType{
  
  ...OrderFields
  
  Products
  @include(if:$orderGetProducts)
  @storage(store:remote)
  {
    ...OrderProduct
  }
  
}

fragment OrderFields on OrderType{
  id
  number_history
  status_id
  status_str
  contractor
  createdby
  createdon
  editedby
  editedon
  manager
  address
  comments
  discount
  positions
  total
  sum
  original_sum
  pay_id
  paysys_invoice_id
  pay_date
  pay_sum
  paysystem_name
}


query OrdersProducts(
  $withPagination:Boolean = false
  $ordersProductsLimit:Int = 10
  $getImageFormats:Boolean = false
  $orderProductGetProduct:Boolean = false
  $orderProductsOrder:Int
){
  
  ...RootOrdersProducts
  
}

fragment RootOrdersProducts on RootType{
  
  ordersProductsList(
    limit:$ordersProductsLimit
    order:$orderProductsOrder
  )
  @include(if:$withPagination)
  @storage(store:remote)
  {
    count
    total
    limit
    page
    object{
      ...OrderProduct
    }
  }
  
  ordersProducts(
    limit:$ordersProductsLimit
    order:$orderProductsOrder
  )
  @skip(if:$withPagination)
  @storage(store:remote)
  {
    ...OrderProduct
  }
  
}

fragment OrderProduct on OrderProductType{
  
  ...OrderProductFields
  
  Product 
  @include(if:$orderProductGetProduct)
  @storage(store:remote)
  {
    ...ProductFields
  }
  
}

fragment ProductFields on MODXResourceType{
  ...MODXResourceFields
}

fragment OrderProductFields on OrderProductType{
  id
  order_id
  product_id
  quantity
  price
}


mutation OrderAddProduct(
  $orderProductId:Int!
  $orderProductsQuantity:Int!
  $orderGetProducts:Boolean = false
  $orderProductGetProduct:Boolean = false
  $getImageFormats:Boolean = false
){
  
  orderAddProduct(
    product_id:$orderProductId
    quantity:$orderProductsQuantity
  )
  @storage(store:remote)
  {
    ...Order
  }
  
}

`;

export default defaultQuery;
