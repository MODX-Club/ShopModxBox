
import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Grid from 'material-ui/Grid';
import Typography from 'material-ui/Typography';
import Paper from 'material-ui/Paper';

import {Link} from 'react-router';

export default class DefaultView extends Component{

	static propTypes = {
		modxResource: PropTypes.object.isRequired,
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
		} = this.props;

		if(!modxResource){
			return null;
		}

		const {
			id,
			pagetitle,
			content,
		} = modxResource;

		return <div>
		
				<Typography
					type="title"
				>
					{pagetitle}
				</Typography>
				 
				{content && <div dangerouslySetInnerHTML={{__html: content}} /> || null}

		</div>
	}
}
