// nav.js — injecte la barre de navigation dans chaque page
// Inclure APRÈS avoir défini `window.CURRENT_PAGE`
(function () {
  const pages = [
    { id: 'home',      label: 'Accueil',          href: 'index.html' },
    { id: 'offers',    label: 'Offres de stage',  href: 'offres.html' },
    { id: 'dashboard', label: 'Mon espace',        href: 'dashboard.html' },
    { id: 'companies', label: 'Entreprises',       href: 'entreprises.html' },
  ];

  const links = pages
    .map(p => `<a href="${p.href}" class="nav-link${window.CURRENT_PAGE === p.id ? ' active' : ''}">${p.label}</a>`)
    .join('');

  document.body.insertAdjacentHTML('afterbegin', `
    <nav>
      <a href="index.html" class="logo">Stage<span>Finder</span></a>
      <div class="nav-links">${links}</div>
      <div class="nav-right">
        <button class="btn" onclick="openModal('login-modal')">Connexion</button>
        <a href="#" class="btn btn-primary">S'inscrire</a>
      </div>
    </nav>

    <!-- Modal Connexion (partagée) -->
    <div id="login-modal" class="modal-overlay" onclick="closeModalOut(event,'login-modal')">
      <div class="modal">
        <div class="modal-title">Connexion</div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input class="form-input" type="email" placeholder="jean.dupont@cesi.fr"/>
        </div>
        <div class="form-group">
          <label class="form-label">Mot de passe</label>
          <input class="form-input" type="password" placeholder="••••••••"/>
        </div>
        <div class="modal-footer">
          <button class="btn" onclick="closeModal('login-modal')">Annuler</button>
          <button class="btn btn-primary">Se connecter</button>
        </div>
      </div>
    </div>
  `);

  // helpers partagés
  window.openModal = id => document.getElementById(id).classList.add('open');
  window.closeModal = id => document.getElementById(id).classList.remove('open');
  window.closeModalOut = (e, id) => { if (e.target.classList.contains('modal-overlay')) closeModal(id); };

  // données partagées (mock)
  window.OFFERS = [
    { id:1, title:'Développeur web full-stack',    company:'Accenture',    initials:'AC', skills:['React','Node.js','SQL'],         date:'15 mars 2025', pay:'600€/mois', candidates:8  },
    { id:2, title:'Intégrateur front-end',         company:'Capgemini',    initials:'CA', skills:['HTML/CSS','JavaScript','Vue.js'], date:'10 mars 2025', pay:'550€/mois', candidates:5  },
    { id:3, title:'Développeur mobile Android',    company:'Sopra Steria', initials:'SS', skills:['Java','Kotlin','Android'],        date:'8 mars 2025',  pay:'580€/mois', candidates:12 },
    { id:4, title:'Data Analyst junior',           company:'Orange Business', initials:'OB', skills:['Python','SQL','Power BI'],    date:'5 mars 2025',  pay:'620€/mois', candidates:7  },
    { id:5, title:'DevOps ingénieur stage',        company:'Thales Group', initials:'TH', skills:['Docker','Kubernetes','CI/CD'],   date:'1 mars 2025',  pay:'650€/mois', candidates:4  },
    { id:6, title:'Développeur Java backend',      company:'Atos',         initials:'AT', skills:['Java','Spring','Hibernate'],     date:'28 fév 2025',  pay:'560€/mois', candidates:9  },
  ];

  window.WISHLIST = new Set(JSON.parse(localStorage.getItem('sf_wishlist') || '[1,4]'));

  window.saveWishlist = () => localStorage.setItem('sf_wishlist', JSON.stringify([...WISHLIST]));

  window.renderOfferCard = function (o) {
    const inWish = WISHLIST.has(o.id);
    return `<div class="offer-card">
      <div class="offer-header">
        <div class="company-avatar">${o.initials}</div>
        <div>
          <div class="offer-title">${o.title}</div>
          <div class="offer-company">${o.company}</div>
        </div>
      </div>
      <div class="offer-tags">
        ${o.skills.map(s => `<span class="tag">${s}</span>`).join('')}
        <span class="tag tag-green">${o.pay}</span>
      </div>
      <div class="offer-footer">
        <span>${o.date} · ${o.candidates} candidats</span>
        <div style="display:flex;gap:8px;align-items:center;">
          <button class="btn btn-sm" onclick="openModal('login-modal')">Postuler</button>
          <button class="wishlist-btn${inWish ? ' active' : ''}" onclick="toggleWish(${o.id},this)">${inWish ? '♥' : '♡'}</button>
        </div>
      </div>
    </div>`;
  };

  window.toggleWish = function (id, btn) {
    if (WISHLIST.has(id)) { WISHLIST.delete(id); btn.textContent = '♡'; btn.classList.remove('active'); }
    else                  { WISHLIST.add(id);    btn.textContent = '♥'; btn.classList.add('active');    }
    saveWishlist();
  };
})();
