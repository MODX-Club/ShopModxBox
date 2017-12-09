/*
	Базовая страница.

	Задача этого компонента - решить, есть такая страница или нет, и если есть, 
	то какой компонент выдать под ее рендеринг
*/

import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Page from '../';

import ProductView from 'modules/Shopmodx/components/Pages/Catalog/Products/Product/View';

export default class DefaultPage extends Page{


	loadData(options = {}){

		const {
      params,
      location,
		} = this.props;

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

		const {
			pathname,
		} = location || {};

		console.log("DefaultPage options", options);

		let result = await provider({
			operationName: "MODXResourceByUri",
			variables: {
				modxResourceUri: pathname,
				modxResourcesStorage: "local",
				getImageFormats: true,
			},
		})
		.then(r => r)
		.catch(e => {
			throw(e);
		});


		if(!result){
			return null;
		}

		const {
			modxResource,
		} = result.data || {};


		if(!modxResource){

			return null;

		}
		else{

			let title;

			const {
				id,
				pagetitle,
				longtitle,
				description,
			} = modxResource;

			title = longtitle || pagetitle;

			Object.assign(result.data, {
				title,
				description,
			});

		}

 
	  return result;

	}


	renderComponent(){

		const {
			...componentState
		} = this.state;

		const {
			modxResource,
		} = componentState;

		if(!modxResource){
			return null;
		}

		const {
			id,
			template,
		} = modxResource;

		let content;

		switch(template){

			case 3:

				content = <ProductView
					key={id}
					{...componentState}
				/>

				break;

		}

		return content;

	}


	render(){

		console.log("DefaultPage render");

		let content = this.renderComponent();

		return super.render(content);

	}

}
