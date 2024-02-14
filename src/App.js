import {useState} from 'react';

const Strings = customers_portfolio_scripts_object;

const App = () => {

    console.log('Strings.customers_portfolio_list', Strings.customers_portfolio_list)

    const [filters, setFilters] = useState({
        company: '',
        category: '',
        country: '',
    });

    const handleFilterChange = (filterName, value) => {
        setFilters({
            ...filters,
            [filterName]: value,
        });
    };

    const filteredItems = Strings.customers_portfolio_list.filter(item => {
        const company_value = new RegExp(filters.company, "i");
        const companyMatch = !filters.company || item.name.match(company_value);
        console.log('filters.category', filters.category)
        const categoryMatch = !filters.category || (item.category?.term_id == filters.category);
        const countryMatch = !filters.country || (item.country?.term_id == filters.country);
        return companyMatch && countryMatch && categoryMatch;
    });

    const get_taxonomy = (taxonomy) => {
        if (taxonomy && taxonomy.length) {
            return taxonomy;
        }
        return null;
    }

    return (
        <>
            <div className="customers-portfolio-header">
                <h4>{Strings.customers_portfolio_title}</h4>
                <div className="customers-portfolio-header-form">
                    <input type="search" className="customers-portfolio-input" onChange={e => handleFilterChange('company', e.target.value)} value={filters.company} placeholder={Strings.find_customer}/>
                    <select className="customers-portfolio-select" onChange={e => handleFilterChange('country', e.target.value)} value={filters.country}>
                        <option value=''>{Strings.all_countries}</option>
                        {Strings.taxonomy_countries.map(country => <option value={country.id}>{country.name}</option>)}
                    </select>
                    <select className="customers-portfolio-select" onChange={e => handleFilterChange('category', e.target.value)} value={filters.category}>
                        <option value=''>{Strings.all_categories}</option>
                        {Strings.taxonomy_categories.map(category => <option value={category.id}>{category.name}</option>)}
                    </select>
                </div>
            </div>
            <div className="customers-portfolio-content">
                {filteredItems.map(item => {
                    return (
                        <div className="customers-portfolio-widget">
                            <img src={(item.logo) ? item.logo : 'https://placehold.jp/18/ebebeb/616161/200x80.png?text=No%20Logo'} alt={item.name}/>
                            <h4>{item.name}</h4>
                            {(item.country) ? <span className="customers-portfolio-widget-country">{item.country.name}</span> : ''}
                            {(item.category) ? <span className="customers-portfolio-widget-category">{item.category.name}</span> : ''}
                        </div>
                    )
                })}
            </div>
        </>
    );
}

export default App;