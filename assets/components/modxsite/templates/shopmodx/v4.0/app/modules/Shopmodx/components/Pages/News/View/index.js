
import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Grid from 'material-ui/Grid';
import Typography from 'material-ui/Typography';
import Paper from 'material-ui/Paper';

import {Link} from 'react-router';

import Pagination from 'modules/Site/components/pagination';


export default class NewsView extends Component{

	static propTypes = {
		modxResource: PropTypes.object.isRequired,
		newsList: PropTypes.object.isRequired,
	};

	
	static contextTypes = {

	};


	constructor(props){

		super(props);

		this.state = {

		};
	}


	componentWillMount(){
	
	}


	componentDidMount(){

	}

	
	render(){

		const {
			modxResource,
			newsList,
			...other
		} = this.props;

		if(!modxResource){
			return null;
		}

		const {
			id,
			pagetitle,
			content,
		} = modxResource;


		const {
			limit,
			total,
			page,
			object: news,
		} = newsList || {}

		let output;

		if(news && news.length){

			output = <div>
				
				{news.map(n => {

					const {
						id,
						pagetitle,
						uri,
						content,
					} = n;

					const link = `/${uri}`;

					return <Paper
						key={id}
						style={{
							padding: 15,
							marginBottom: 20,
						}}
					>
						
						<Link
							to={link}
							href={link}
							title={pagetitle}
						>
							{pagetitle}
						</Link>


						{content && <div dangerouslySetInnerHTML={{__html: content}} /> || null}

					</Paper>

				})}

				<div
		    	style={{
		    		textAlign: "center",
		    	}}
		    >
		    	
		    	<Pagination
		      	page={parseInt(page) || 1}
			      limit={limit}
			      total={total}
			    />

		    </div>

			</div>

		}

		return <div>
		
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
