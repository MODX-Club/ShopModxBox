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
			req: nullReq,
			...debugOptions
		} = options;

		const {
      params,
      location,
			
			/*
      	Серверный объект запроса, содержащий заголовки.
      	Важно передавать этот объект в запрос, чтобы на сервере передавались и кукисы пользователя
      */
      req,		
		} = options;

		const {
			pathname,
		} = location || {};

		let result = await provider({
			operationName: "MODXResourceByUri",
			variables: {
				modxResourceUri: pathname,
				modxResourcesStorage: "local",
				getImageFormats: true,
			},
			req,
		})
		.then(r => r)
		.catch(e => {
			throw(e);
		});


		if(!result){
			return null;
		}


		// Получаем текущего пользователя
		
		await provider({
			operationName: "CurrentUser",
			variables: {
			},
			req,
		})
		.then(r => {

			const {
				user,
			} = r.data;

			Object.assign(result.data, {
				user,
			});

			// console.log("DefaultPage CurrentUser", r);

		})
		.catch(e => {
			throw(e);
		});


		// console.log("DefaultPage result", result);

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

		// console.log("DefaultPage result.data", result.data);
 
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

		// console.log("DefaultPage render");

		let content = this.renderComponent();

		return super.render(content);

	}

}
