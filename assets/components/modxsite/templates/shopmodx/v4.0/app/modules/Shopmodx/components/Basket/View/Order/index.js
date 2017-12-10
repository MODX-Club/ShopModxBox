

/*
	Оформление заказа
*/

import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Grid from 'material-ui/Grid';
import Typography from 'material-ui/Typography';
import Paper from 'material-ui/Paper';
import TextField from 'material-ui/TextField';
import Button from 'material-ui/Button';

import {Link} from 'react-router';

import Table, { TableBody, TableCell, TableHead, TableRow } from 'material-ui/Table';

import NumberFormat from 'react-number-format';

export default class OrderView extends Component{

	static propTypes = {

	};

	
	static contextTypes = {
		user: PropTypes.object.isRequired,
		order: PropTypes.object.isRequired,
		recalculateBasket: PropTypes.func.isRequired,
		submitOrder: PropTypes.func.isRequired,
		documentActions: PropTypes.object.isRequired,
	};


	constructor(props){

		super(props);

		this.state = {

		};
	}


	componentWillMount(){
	
		const {
			user: {
				user: currentUser,
			},
		} = this.context;

		const {
			...userData
		} = currentUser || {};

		Object.assign(this.state, {
			...userData
		});

	}


	componentDidMount(){

	}


	onChange(event){

		const {
			name,
			value,
		} = event.target;

		this.setState({
			[`${name}`]: value,
		});

	}


	// Пересчитываем корзину
	async addToBasket(item, quantity){

		const {
			recalculateBasket,
		} = this.context;

		await recalculateBasket(item, {
			quantity,
		});

		this.forceUpdate();


	}


	async submit(){

		// console.log("submit");

		const {
			submitOrder,
		} = this.context;

		let errors = {};

		const {
			...params
		} = this.state;

		const result = await submitOrder(params)
		.then(r => {

			const {
				documentActions,
			} = this.context;

			documentActions.addInformerMessage({
				type: "success",
				text: "Заказ успешно оформлен", 
			});

		})
		.catch(e => {

			const {
				errors: responseErrors,
			} = e;

			responseErrors && responseErrors.map(error => {

				const {
					id,
					msg,
				} = error || {};

				if(!id || !msg){
					return;
				}

				errors[id] = msg;

			});

			console.error(e);
		});

		console.log("Order submit result", result);

		this.setState({
			errors,
		});

	}


	onFocus = (event) => {

		console.log("onFocus event", event);

		const {
			name,
		} = event.target;

		let {
			errors,
		} = this.state;

		if(errors && errors[name]){

			errors[name] = null;

			this.setState({
				errors,
			});

		}

	}

	
	render(){

		const {
			modxResource,
		} = this.props;

		if(!modxResource){
			return null;
		}

		const {
			order,
		} = this.context;

		const {
			id,
			pagetitle,
			content,
		} = modxResource;


		const {
			fullname,
			email,
			phone,
			address,
			comments,
			errors,
		} = this.state;


		let output;

		if(!order || !order.Products || !order.Products.length){
			output = <Typography
				type="subheading"
				style={{
					color: "red",
					marginTop: 20,
					marginBottom: 20,
				}}
			>
				Корзина пуста
			</Typography>
		}
		else{

			const {
				Products,
				status_id,
			} = order;

			
			let orderForm;


			if(status_id === 1){

				orderForm = <Grid
					container
				>
					
					<Grid
						item
						xs={12}
						md={6}
						lg={4}
					>

						<TextField 
							label="Имя"
							name="fullname"
							value={fullname || ""}
							helperText={errors && errors.fullname || "Как вас зовут?"}
							error={errors && errors.fullname ? true : false}
							onChange={::this.onChange}
							onFocus={() => {
								this.onFocus({
									target: {
										name: "fullname",
									},
								});
							}}
						/>

						<TextField 
							label="Емейл"
							name="email"
							value={email || ""}
							helperText={errors && errors.email || "Адрес электронной почты"}
							error={errors && errors.email ? true : false}
							onChange={::this.onChange}
							onFocus={() => {
								this.onFocus({
									target: {
										name: "email",
									},
								});
							}}
						/>

						<TextField 
							label="Телефон"
							name="phone"
							placeholder={"Номер телефона для связи"}
							helperText={errors && errors.phone || "Номер телефона для связи"}
							error={errors && errors.phone ? true : false}
							onChange={::this.onChange}
							onFocus={() => {
								this.onFocus({
									target: {
										name: "phone",
									},
								});
							}}
						/>

						<TextField 
							multiline
							label="Адрес"
							name="address"
							value={address || ""}
							helperText={errors && errors.address || "Адрес доставки"}
							error={errors && errors.address ? true : false}
							onChange={::this.onChange}
							onFocus={() => {
								this.onFocus({
									target: {
										name: "address",
									},
								});
							}}
						/>

						<TextField 
							multiline
							label="Комментарии"
							name="comments"
							value={comments || ""}
							helperText={errors && errors.comments || "Любая дополнительная информация"}
							error={errors && errors.comments ? true : false}
							onChange={::this.onChange}
							onFocus={() => {
								this.onFocus({
									target: {
										name: "comments",
									},
								});
							}}
						/>
						
					</Grid>
					
					<Grid
						item
						xs={12}
					>

						<Button
							raised
							onClick={::this.submit}
						>
							Отправить
						</Button>

					</Grid>

				</Grid>

			}
			else{

				orderForm = <div>
					
					<Typography
						type="subheading"
						style={{
							color: "green",
						}}
					>
						Заказ оформлен
					</Typography>

				</div>

			}



			output = <div>
				
				<Paper 
					style={{
						padding: 10,
						overflow: "auto",
					}}
				>
		      <Table>
		        <TableHead>
		          <TableRow>
		            <TableCell></TableCell>
		            <TableCell>Наименование</TableCell>
		            <TableCell>Количество</TableCell>
		            <TableCell>Стоимость</TableCell>
		            <TableCell>Сумма</TableCell>
		          </TableRow>
		        </TableHead>
		        <TableBody>
		          {Products && Products.map(n => {

		          	const {
		          		id,
		          		quantity,
		          		price,
		          		Product,
		          	} = n;

		          	const {
		          		pagetitle,
		          		uri,
		          		imageFormats,
		          	} = Product;

		          	const {
		          		thumb,
		          	} = imageFormats || {};

		          	const sum = quantity * price;

		          	const link = `/${uri}`;

		            return (
		              <TableRow key={id}>
		                <TableCell>
		                	<Link
		                		to={link}
		                		href={link}
		                		title={pagetitle}
		                	>
			                	{thumb && <img 
			                		src={thumb}
			                	/> || ""}
		                	</Link>
		                </TableCell>
		                <TableCell>
		                	<Link
		                		to={link}
		                		href={link}
		                		title={pagetitle}
		                	>
		                		{pagetitle}
		                	</Link>
		                </TableCell>
		                <TableCell >
		                	
		                	<TextField 
		                		value={quantity || 0}
		                		type="number"
		                		name="quantity"
		                		disabled={status_id === 1 ? false : true}
		                		onChange={event => {

		                			let {
		                				value: quantity,
		                			} = event.target;

		                			quantity = quantity > 0 ? quantity : 0;

		                			this.addToBasket(n, quantity);

		                		}}
		                	/>

		                </TableCell>
		                <TableCell >
		                	<NumberFormat 
		                		value={price}
												thousandSeparator=" "
		                		displayType="text"
		                	/> руб.
		                </TableCell>
		                <TableCell >
		                	<NumberFormat 
		                		value={sum}
												thousandSeparator=" "
		                		displayType="text"
		                	/> руб.
		                </TableCell>
		              </TableRow>
		            );
		          }) || null}
		        </TableBody>
		      </Table>
		    </Paper>

		    {orderForm}

			</div>

		}

		return <div
			style={{
				width: "100%",
			}}
		>

			<Typography
				type="title"
				style={{
					marginBottom: 30,
				}}
			>
				{pagetitle}
			</Typography>
			 
			{output}

		</div>
	}
}
