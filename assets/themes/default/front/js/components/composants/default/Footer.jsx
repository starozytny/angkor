import React, {Component} from 'react';
import Routing            from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

export class Footer extends Component {
    render () {
        return <>
            <footer>
                <div className="footer">
                    <div className="footer-menu">
                        <div className="footer-logo">
                            <a href={Routing.generate('app_homepage')} title="Angkor restaurant">
                                <img src="../../build/themes/default/front/images/logo.svg" alt="Logo"/>
                            </a>
                            <span>Angkor Restaurant Aix-en-provence -  10 Place forum des Cardeurs, 13001 Aix-en-Provence </span>
                        </div>
                        <div className="footer-items">
                            <a className="btn-icon" href="https://www.tripadvisor.fr/Restaurant_Review-g187209-d2307367-Reviews-Angkor-Aix_en_Provence_Bouches_du_Rhone_Provence_Alpes_Cote_d_Azur.html" target="_blank" rel="nofollow">
                                <span className="icon-tripadvisor"></span><span className="tooltip">Tripadvisor</span>
                            </a>
                            <a className="btn-icon" href="https://www.facebook.com/Angkor-Restaurant-121484761272063" target="_blank" rel="nofollow">
                                <span className="icon-facebook"></span><span className="tooltip">Facebook</span>
                            </a>
                            <a className="btn-icon" href="" target="_blank">
                                <span className="icon-instagram"></span><span className="tooltip">Instagram</span>
                            </a>
                        </div>
                        <div className="footer-items">
                            <a rel="nofollow" href={Routing.generate('app_mentions')}>Mentions légales</a>
                            <a rel="nofollow" href={Routing.generate('app_politique')}>Politique de confidentialité</a>
                            <a rel="nofollow" href={Routing.generate('app_cookies')}>Gestion des cookies</a>
                            <a rel="nofollow" href={Routing.generate('app_login')}>Espace client</a>
                        </div>
                    </div>
                    <div className="footer-horaires">
                        <p className="title">Horaire</p>
                        <div className="items">
                            <div className="item">
                                <div className="day">Mardi - Samedi</div>
                                <div className="hours">11h30 à 14h30</div>
                            </div>
                            <div className="item">
                                <div className="day">Mardi - Samedi</div>
                                <div className="hours">18h30 à 21h00</div>
                            </div>
                            <div className="item">
                                <div className="day">Dimanche</div>
                                <div className="hours">18h30 à 21h30</div>
                            </div>
                            <div className="item">
                                <div className="day">Lundi</div>
                                <div className="hours">Fermé</div>
                            </div>
                        </div>
                    </div>
                    <div className="copyright">Copyright © 2020 - <a href="https://chanbora-chhun.fr" target="_blank">Chanbora Chhun</a></div>
                </div>
            </footer>
        </>
    }
}