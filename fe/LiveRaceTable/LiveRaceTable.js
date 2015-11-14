import React from 'react';
import LiveRaceTablePair from './LiveRaceTablePair.js'

export default class LiveRaceTable extends React.Component {

    constructor() {
        super();

        this.state = {
            pairs: [
                {
                    id: 1,
                    startNum: 1,
                    members: [
                        {
                            name: 'Petr',
                            surname: 'Sladek',
                            nickname: 'Peggy',
                        },
                        {
                            name: 'Tomáš',
                            surname: 'Páteček',
                            nickname: 'Friday'
                        }
                    ],
                    rank: 1
                },
                {
                    id: 2,
                    startNum: 2,
                    members: [
                        {
                            name: 'Libor',
                            surname: 'Čermák',
                            nickname: null
                        },
                        {
                            name: 'Tereza',
                            surname: 'Kociánová',
                            nickname: 'Kuře'
                        }
                    ],
                    rank: 2
                },
                {
                    id: 3,
                    startNum: 3,
                    members: [
                        {
                            name: 'Anna',
                            surname: 'Jančevová',
                            nickname: null
                        },
                        {
                            name: 'Kristýna',
                            surname: 'Nevím',
                            nickname: null
                        }
                    ],
                    rank: 3
                },
                {
                    id: 4,
                    startNum: 4,
                    members: [
                        {
                            name: 'Nekdo',
                            surname: 'Jinej',
                            nickname: 'Dalsší'
                        },
                        {
                            name: 'Nekdo',
                            surname: 'Jinej',
                            nickname: 'Dalsší'
                        }
                    ],
                    rank: 4
                },
            ]
        };

        this.randomOrder = this.randomOrder.bind(this);
        this.toFirst = this.toFirst.bind(this);
    }

    randomOrder()  {

        var pairs = this.state.pairs;

        function shuffle(o){
            for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
            return o;
        }

        const rates = shuffle(Array.apply(null, Array(pairs.length)).map(function (_, i) {return i;}));

        pairs = pairs.map(function(pair, i) {
            pair.rank = rates[i] + 1;
            return pair;
        });

        this.setState({
            pairs: pairs
        })
    }

    toFirst(id) {

        var pairs = this.state.pairs;

        // puvodni rank
        const rank = pairs.find(pair => {return pair.id == id}).rank;

        pairs = pairs.map(function(pair) {
            if(pair.id == id)
            {
                pair.rank = 1;
            }
            else if(pair.rank < rank)
            {
                pair.rank++;
            }
            return pair;
        });

        this.setState({
            pairs: pairs
        });

    }



    render() {

        const style = {
            position: 'relative',
            height: this.state.pairs.length * 100,
        };

        return (
            <div>
                <div style={style}>
                    {this.state.pairs.map(pair => {
                        return <LiveRaceTablePair
                                    members={pair.members}
                                    startNum={pair.startNum}
                                    rank={pair.rank}
                                    id={pair.id}
                                    toFirst={this.toFirst}
                                    key={pair.id} />;
                    })}
                </div>
                <button className="btn btn-info" onClick={this.randomOrder}>Náhodné pořadí</button>
            </div>
        );
    }



}