import React, { Component } from 'react';

import PropTypes from 'prop-types';

import RendererPrototype from 'shopmodx-react/components/App/Renderer';

import Informer from 'structor-templates/components/Informer';

import Auth from 'react-cms/src/app/components/Auth';

import Header from './Header';

import Typography from 'material-ui/Typography';

let {
	...defaultProps
} = RendererPrototype.defaultProps || {};


Object.assign(defaultProps, {
	Header,
});


let {
	...contextTypes
} = RendererPrototype.contextTypes || {};


Object.assign(contextTypes, {
	isDemo: PropTypes.bool.isRequired,
});


export default class Renderer extends RendererPrototype{

	static defaultProps = defaultProps;

	static contextTypes = contextTypes;


	constructor(props){

		super(props);

		Object.assign(this.state, {
			showWallet: false,
		});

	}


	render(){

		const {
			isDemo,
		} = this.context;

		// let output = super.render();

		if(!isDemo){
			// return output;
			
			return super.render();

		}



    const {
      inited,
      children,
      authOpen,
      notifications_store,
      Header,
    } = this.props;

    const {
			showWallet,
    } = this.state;

    return <div
      className="MainApp"
    >

			{Header && <Header /> || null}
			
			<div
				style={{
					margin: "20px 0",
				}}
			>

				<Typography
					type="subheading"
				>
					<span
						style={{
							color: "red",
						}}
					>Это демо-версия магазина.</span> Если вы хотите поддержать проект, пишите на почту <a href="mailto:n.lanes@modxclub.ru">n.lanes@modxclub.ru</a>.

					<p>
						Можно поделиться <a 
							href="javascript:;"
							onClick={e => {
								e.preventDefault();
								this.setState({
									showWallet: !showWallet,
								});
							}}
							style={{
								textDecoration: "underline",
							}}
						>эфиром</a>.
					</p>
					
					{showWallet && <p>
						<b>Адрес кошелька:</b> 0x4c791666351Ec3b223acF96C9d9BE431679E5C04
					</p> || null}

				</Typography>

				<p>
					ShopModxBox - это бесплатный готовый интернет-магазин на базе MODX Revolution + React-js + GraphQL.
				</p>

				<p>
					Все вопросы можно задавать на сайте <a href="https://modxclub.ru" target="_blank">modxclub.ru</a>
				</p>

			</div>
      
      {children}

      <Auth 
        open={authOpen}
      />

      <Informer
        store={notifications_store}
      />

		</div>;
		

		// return <div>
			
		// 	{output}

		// 	<div>

		// 		<Typography
		// 			type="subheading"
		// 		>
		// 			Если вы хотите поддержать проект, пишите на почту <a href="mailto:n.lanes@modxclub.ru">n.lanes@modxclub.ru</a>
		// 		</Typography>

		// 	</div>

		// </div>

	}

}
