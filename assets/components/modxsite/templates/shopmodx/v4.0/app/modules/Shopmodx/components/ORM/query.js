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

query MODXResources(
  $modxResourcesLimit:Int = 10
  $modxResourcesPage:Int
  $withPagination:Boolean = false
){
  
  ...RootMODXResources
  
}

fragment RootMODXResources on RootType{
  
  modxResourcesList(
    limit: $modxResourcesLimit
    page: $modxResourcesPage
  ) @include(if:$withPagination)
  {
    count
    total
    limit
    page
    object{
      ...MODXResources
    }
  }
  
  modxResources(
    limit: $modxResourcesLimit
    page: $modxResourcesPage
  ) @skip(if:$withPagination)
  {
    ...MODXResources
  }
  
}

fragment MODXResources on MODXResourceType{
  
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
  properties
  tvs
  _other
  
}



`;

export default defaultQuery;
