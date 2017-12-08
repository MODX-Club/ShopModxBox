

import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Page from '../';

export default class MainPage extends Page{
	


	loadData(options = {}){

		// return this.loadData(options);

		const {
      params,
      location,
		} = this.state;

		Object.assign(options, {
      params,
      location,
		});

		return super.loadData(options);

	}

	
	async loadServerData(provider, options = {}){

		let {
			...debugOptions
		} = options;

		const {
      params,
      location,
		} = options;

		console.log("MainPage options", options);

		let result;

		result = {
			data: {
	      params,
	      location,
			}
		};

		// Получаем список компаний
	  // const result = await provider({

			// operationName: "Albums",
			// variables: {
			// 	limit,
	  //     withPagination: true,
	  //     albumsLimit: 12,
			// 	getImageFormats: true,
			// 	albumsPage: page,
			// },
	  // })
	  // .then(r => {

	  //   return r;

	  // })
	  // .catch(e => {
	  //   throw(e);
	  // });


	  // if(result && result.data){

	  // 	let title;

	  // 	const {
	  // 		albumsList,
	  // 	} = result.data;

	  // 	const {
	  // 		object: albums,
	  // 	} = albumsList || {};

	  // 	if(!albums || !albums.length){
	  // 		return null;
	  // 	}

	  // 	title = title || "Фотоальбомы";

	  // 	if(page > 1){

	  // 		title = `${title}, страница ${page}`;

	  // 	}

  	// 	Object.assign(result.data, {
  	// 		title,
  	// 	});

	  // }
	  // else{
	  // 	return null;
	  // }


	  return result;

	}

}
