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


}