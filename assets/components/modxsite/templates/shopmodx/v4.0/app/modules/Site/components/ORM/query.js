import shopModxDefaultQuery from 'shopmodx-react/components/ORM/query.js';

import mergeQuery from 'react-cms-graphql-utils/src/mergeQuery';


const extendedQuery = ``;


const defaultQuery = mergeQuery(shopModxDefaultQuery, extendedQuery);

export default defaultQuery;
