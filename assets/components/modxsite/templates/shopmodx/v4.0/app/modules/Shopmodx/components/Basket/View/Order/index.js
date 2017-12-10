

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

		console.log("submit");

		const {
			submitOrder,
		} = this.context;

		const result = await submitOrder()
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
			console.error(e);
		});

		// console.log("result", result);

		this.forceUpdate();

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
							placeholder={"Как вас зовут?"}
							onChange={::this.onChange}
						/>

						<TextField 
							label="Емейл"
							name="email"
							value={email || ""}
							placeholder={"Адрес электронной почты"}
							onChange={::this.onChange}
						/>

						<TextField 
							label="Телефон"
							name="phone"
							value={phone || ""}
							placeholder={"Номер телефона для связи"}
							onChange={::this.onChange}
						/>

						<TextField 
							multiline
							label="Адрес"
							name="address"
							value={address || ""}
							placeholder={"Адрес доставки"}
							onChange={::this.onChange}
						/>

						<TextField 
							multiline
							label="Комментарии"
							name="comments"
							value={comments || ""}
							placeholder={"Любая дополнительная информация"}
							onChange={::this.onChange}
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
