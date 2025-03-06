// public/assets/js/components/Portfolio/PortfolioView.js
import React from 'react';
import MainAlbum from './MainAlbum';
import { styles } from './styles';

export const PortfolioView = ({ albums }) => {
    return (
        <>
            <style>{styles}</style>
            <div className="portfolio-view">
                {albums.map(album => (
                    <MainAlbum key={album.id} album={album} />
                ))}
            </div>
        </>
    );
};

// public/assets/js/components/Portfolio/MainAlbum.js
import React, { useState } from 'react';
import { ChevronDown, ChevronRight, Camera } from 'lucide-react';
import SubAlbum from './SubAlbum';

const MainAlbum = ({ album }) => {
    const [isExpanded, setIsExpanded] = useState(false);
    const hasSubAlbums = album.subAlbums && album.subAlbums.length > 0;

    return (
        <div className="main-album">
            {/* Le reste du code du MainAlbum... */}
        </div>
    );
};

export default MainAlbum;

// public/assets/js/components/Portfolio/SubAlbum.js
import React from 'react';
import { MapPin } from 'lucide-react';

const SubAlbum = ({ album }) => {
    return (
        <div className="card album-card">
            {/* Le reste du code du SubAlbum... */}
        </div>
    );
};

export default SubAlbum;

// public/assets/js/components/Portfolio/styles.js
export const styles = `
    .portfolio-view {
        margin: 2rem 0;
    }
    /* Le reste des styles... */
`;

// public/assets/js/portfolio-init.js
import { PortfolioView } from './components/Portfolio/PortfolioView';

document.addEventListener('DOMContentLoaded', function() {
    const rootElement = document.getElementById('portfolio-root');
    const portfolioData = window.portfolioData || [];

    ReactDOM.render(
        React.createElement(PortfolioView, { albums: portfolioData }),
        rootElement
    );
});