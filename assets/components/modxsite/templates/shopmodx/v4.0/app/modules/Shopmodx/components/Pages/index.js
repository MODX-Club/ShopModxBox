
import React, {Component} from 'react';

import PropTypes from 'prop-types';

import PageLayout from 'react-cms/src/app/components/Page';

export default class ShopmodxPage extends PageLayout{




	async loadServerData(provider, options = {}){

		// Для всех страниц по умолчанию
	  return {
	  	data: {
	  		title: "ShopModxBox",
	  	},
	  };

	}


	render(childContent){

		return super.render(<div
			style={{
		    display: "flex",
		    flexDirection: "row",
		    flexGrow: 1,
			}}
		>
			{childContent}
		</div>);

	}


}