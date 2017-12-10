

import './styles/styles.less';

import React, {Component} from 'react';

import PropTypes from 'prop-types';

import PaginationPrototype from 'react-cms-data-view/src/Pagination';

import { Link, browserHistory } from "react-router";

// import URL from 'url-parse';

// window.URL = URL;

// window.browserHistory = browserHistory;




// let strSize = 5;
const paginationItemStyle = {
  cursor: 'pointer',
}

class Pagination extends PaginationPrototype{

	static contextTypes = {
    router: PropTypes.object.isRequired,
	}

	getNewLocation = (page) => {


		const {
			router,
		} = this.context;

		if(!router){
			return null;
		}

		const {
			location,
		} = router;

		// let {
		// 	location,
		// } = router;

		// if(typeof window != "undefined"){
		// 	location = window.location.href;
		// }
		// else{

		// }


		// if(!location){
		// 	console.error("Location not defined");
		// 	return;
		// }


  // 	location = router.getCurrentLocation();
  	
  	let newLocation = router.createLocation(location);

  	newLocation.query.page = page > 1 ? page : undefined;

  	return router.createPath(newLocation);


		// if(browserHistory){

	 //  	location = browserHistory.getCurrentLocation();
	 //  	location.query.page = page > 1 ? page : undefined;

	 //  	return browserHistory.createPath(location);
		// }
		// else{

		// 	return;

		// 	let url = new URL(location, true);

		// 	if(!url){
		// 		console.error("Error parse url");
		// 		return;
		// 	} 

		// 	// Location = url;

		// 	// let blogs = [];



		// 	let query = url.query || {};

		// 	if(page == 1 || !parseInt(page)){
		// 		delete query.page;
		// 	}
		// 	else{
		// 		Object.assign(query, {
		// 			page,
		// 		});
		// 	}


		// 	url.set("query", query);

		// 	return url.href;
		// }


		// if(page == 1){
		// 	page = undefined;
		// }

		// if(browserHistory){

	 //  	let location = browserHistory.getCurrentLocation();
	 //  	location.query.page = page;

	 //  	return browserHistory.createPath(location);
		// }
		// else{
		// 	return `?page=${page}`
		// }

		// return;
	}

	render(){


    let {show, page, limit, total} = this.props;

    const classes = {

    };

    if(!show || !page || !limit || !total){
      return null;
    }
 
    let pages = Math.ceil(total/limit);

    if(pages < 2){
    	return null;
    }

    var rows = [];
    if(page > 1){
      // rows.push(<li key='page-1' className="control"><span style={paginationItemStyle} onClick={this.setPage.bind(this,1)}>Первая</span></li>);
    	
    	var href = this.getNewLocation(1);

     //  rows.push(<li key='page-1' className="control">
     //  	<Link style={paginationItemStyle} to={href} href={href}>Первая</Link>
    	// </li>);

      // rows.push(<li key='page-1-0' className="control"><span style={paginationItemStyle} onClick={this.setPage.bind(this,page-1)}>«</span></li>);

    	var href = this.getNewLocation(page - 1);

      rows.push(<li key='page-1-0' className="control">
      	<Link style={paginationItemStyle} to={href} href={href}>«</Link>
      </li>);
    }

    var lstr = false;
    var rstr = false;
    for(var i = 1; i <= pages; i++){
      if(
        (
          page > 2
          && i < page -1
          && i > 1
        )
        || (
          pages - page > 3
          && i > page +1
          && i < pages -1
        )
      ){
        if(!lstr && i > 1 && i < page){
          rows.push(<li key={i}><span>...</span></li>);
          lstr = true;
        }
        if(!rstr && i > page && i < pages){
          rows.push(<li key={i}><span>...</span></li>);
          rstr = true;
        }
      }
      else {
        // rows.push(<li key={i} className={i != page || 'active'}><span style={paginationItemStyle} onClick={this.setPage.bind(this,i)}>{i}</span></li>);

      	var href = this.getNewLocation(i);

        rows.push(<li key={i} className={i != page || 'active'}>
        	<Link style={paginationItemStyle} to={href} href={href}>{i}</Link>
      	</li>);
      }
    }
    if(page < pages){
      // rows.push(<li key={'page-'+ pages +'-0'} className="control"><span style={paginationItemStyle} onClick={this.setPage.bind(this,page+1)}>»</span></li>);

      var href = this.getNewLocation(page+1);

      rows.push(<li key={'page-'+ pages +'-0'} className="control">
      	<Link style={paginationItemStyle} href={href} href={href}>»</Link>
      </li>);

      // rows.push(<li key={'page-'+ pages} className="control"><span style={paginationItemStyle} onClick={this.setPage.bind(this,pages)}>Последняя</span></li>);

      // var href = this.getNewLocation(pages);

     //  rows.push(<li key={'page-'+ pages} className="control">
     //  	<Link style={paginationItemStyle} to={href} href={href}>Последняя</Link>
    	// </li>);
    }

    return (
      <div className={classes.Pagination}>
        <ul className="pagination">
          {rows}
        </ul>
      </div>
    )
  }
}

export default class MyPagination extends Component{

	static contextTypes = {
		// classes: PropTypes.object.isRequired,
	};

	static propTypes = {
		page: PropTypes.number.isRequired,
		limit: PropTypes.number.isRequired,
		total: PropTypes.number.isRequired,
	};

	constructor(props){

		super(props);

		this.state = {}
	}

	componentWillMount(){

	}

	componentDidMount(){

	}

  componentDidUpdate(){
 
  }

  // onChangePage(page){

  // 	if(typeof window != 'undefined'){

  // 		if(page == 1){
  // 			page = undefined;
  // 		}

	 //  	let location = browserHistory.getCurrentLocation();
	 //  	location.query.page = page;

	 //  	window.location = browserHistory.createPath(location);
  // 	}

  // 	return;
  // }

	render(){

		let {
			...other
		} = this.props;

		// let {
		// 	classes,
		// } = this.context;

		return <Pagination 
			// classes={classes}
			// onChangePage={page => this.onChangePage(page)}
			{...other}
		/>;
	}
} 
