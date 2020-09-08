import React, {Component} from 'react';
import Routing from '../../../../../../../../public/bundles/fosjsrouting/js/router.min.js';
import {Page} from '../../../../../super/js/components/composants/page/Page';
import ActionsClassique from '../../../../../react/functions/actions_classique';

export class ContactList extends Component {
    constructor (props){
        super(props)

        this.state = {
            cardOpened: null
        }

        this.handleClick = this.handleClick.bind(this)
        this.handleDelete = this.handleDelete.bind(this)
    }

    handleClick = (e) => {
        let id = e.currentTarget.dataset.id;
        let seen = e.currentTarget.dataset.seen;
        this.setState({ cardOpened: (id == this.state.cardOpened) ? null : id })

        if(seen == "false"){
            this.props.onUpdateSeen(id)
        }
    }

    handleDelete = (e) => {
        this.props.onDelete(e.currentTarget.dataset.id)
    }

    render () {
        const {demandes} = this.props
        const {cardOpened} = this.state

        let items = <div class="alert alert-primary">Aucun enregistrement.</div>;

        if(demandes.length != 0){
            items = demandes.map(elem => {

                let bodyOpened = (cardOpened != null && cardOpened == elem.id) ? true : false;
    
                return <div className={"card1 card1-" + bodyOpened} key={elem.id}>
                    <div className="card1-header">
                        <div className="card1-header-title">
                            <div className="title">{elem.firstname}</div>
                            <div className="subtitle">{elem.email}</div>
                        </div>
                        <div className="card1-header-toggle-body" data-id={elem.id} data-seen={elem.isSeen} onClick={this.handleClick} >
                            <div className="btn-icon">
                                <span className="icon-chevron-down"></span>
                            </div>
                        </div>
                    </div>
                    <div className="card1-body">
                        <p>{elem.message}</p>
                    </div>
                    <div className="card1-footer">
                        <div className="card1-footer-left">
                            <div className="btn-isSeen">
                                    {elem.isSeen ? <span className="icon-vision"></span> : <span className="icon-vision-not"></span>}
                                </div>
                                <div className="date-discret">{elem.createAtString}</div>
                            </div>
                        <div className="card1-footer-right">
                            <div className="btn-icon" data-id={elem.id} onClick={this.handleDelete}>
                                <span className="icon-trash"></span>
                            </div>
                        </div>
                    </div>
                </div>
            })
        }

        return <> {items} </>
    }
}

export class Contact extends Component {
    constructor (props){
        super(props);

        let data = JSON.parse(JSON.parse(props.demandes));
        let dataList = data.slice(0, 12);

        this.state = {
            dataImmuable: data,
            data: data,
            dataList: dataList,
            tailleList: data.length,
        }

        this.handleUpdateList = this.handleUpdateList.bind(this);
        this.handleUpdateSeen = this.handleUpdateSeen.bind(this);
        this.handleDelete = this.handleDelete.bind(this);
    }

    handleUpdateList = (dataList) => { this.setState({ dataList: dataList }) }

    handleUpdateSeen = (id) => {
        ActionsClassique.classiqueUpdateSeen(this, Routing.generate('admin_contact_update_seen', {'contact': id}), id);
    }

    handleDelete = (id) => {
        ActionsClassique.classiqueDelete(this, Routing.generate('admin_contact_delete', {'contact': id}), id);
    }

    render (){
        const {data, dataImmuable, dataList, tailleList} = this.state;

        let content = <div className="liste liste-rgpd">
            <ContactList demandes={dataList} onDelete={this.handleDelete} onUpdateSeen={this.handleUpdateSeen} />
        </div>

        return <>
            <Page content={content} 
                havePagination="true" taille={tailleList} itemsPagination={data} perPage="12" onUpdate={this.handleUpdateList}
            />
        </>
    }
}