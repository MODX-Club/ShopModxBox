import React, { Component } from 'react';

import PropTypes from 'prop-types';

import RendererPrototype from 'shopmodx-react/components/App/Renderer';


let {
	...defaultProps
} = RendererPrototype.defaultProps || {};


Object.assign(defaultProps, {
});


let {
	...contextTypes
} = RendererPrototype.contextTypes || {};


Object.assign(contextTypes, {
});


export default class Renderer extends RendererPrototype{

	static defaultProps = defaultProps;

	static contextTypes = contextTypes;

}
