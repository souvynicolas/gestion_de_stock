
console.log("JS articles chargé");console.log("JS externe chargé");
document.addEventListener('DOMContentLoaded', function () {
const gestionTableau = selectLigneTableau();


    document.querySelectorAll('[data-open]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-open');
            const fenetre = document.getElementById(id);

            if (fenetre) {
                fenetre.style.display = 'flex';
            }
        });
    });

    document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const fenetre = btn.closest('.fenetre');

            if (fenetre) {
                fenetre.style.display = 'none';
            }
        });
    });

    function selectLigneTableau(){
    
    let idSelect= null;
    let ligneSelect= null;

    const lignes = document.querySelectorAll('#tableau tbody tr');
    

    lignes.forEach(ligne => {
        ligne.addEventListener('click', function (){
            if(ligneSelect) {
                ligneSelect.classList.remove('selected');
            }
            this.classList.add('selected');
            ligneSelect = this;
            idSelect = this.dataset.id;
        });
    });
    return {
        getId: () => idSelect,
        getLigne: () => ligneSelect,
    };
}


function remplirFormulaireDepuisLigne(formulaire, ligne) {
    const champs = formulaire.querySelectorAll('[data-fill]');

    champs.forEach(champ => {
        const cle = champ.dataset.fill;
        champ.value = ligne.dataset[cle] || '';
    });
}


const btnModifPce = document.getElementById('btn_mdf_pce');
const fenetrePce = document.getElementById('fenetre_mdf_pce');
const formPce = document.getElementById('form_mdf_pce');
if (btnModifPce && fenetrePce && formPce) {
btnModifPce.addEventListener('click', () => {
    const piecesCochees = document.querySelectorAll('input[name="pieces_check_ids[]"]:checked');

    if (piecesCochees.length === 0) {
            alert("Sélectionne une pièce");
            return;
        }

        if (piecesCochees.length > 1) {
            alert("Tu ne peux modifier qu'une seule pièce à la fois");
            return;
        }

        const pieceId = piecesCochees[0].value;
        const ligne = document.querySelector(`#tableau tbody tr[data-id="${pieceId}"]`);

        if (!ligne) {
            alert("Sélectionne une ligne");
            return;
        }

        remplirFormulaireDepuisLigne(formPce, ligne);
        fenetrePce.style.display = 'flex';
    });
}

const btnSupprimerPce = document.getElementById('btn_spr_pce');
const fenetreSupPce = document.getElementById('fenetre_spr_pce');
const containerIdsSupPce = document.getElementById('spr_pce_ids_container');

if (btnSupprimerPce && fenetreSupPce && containerIdsSupPce) {
    btnSupprimerPce.addEventListener('click', () => {
        const piecesCochees = document.querySelectorAll('input[name="pieces_check_ids[]"]:checked');

        if (piecesCochees.length === 0) {
            alert("Coche au moins une pièce à supprimer");
            return;
        }

        containerIdsSupPce.innerHTML = '';

        piecesCochees.forEach((checkbox) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'spr_pce_ids[]';
            input.value = checkbox.value;
            containerIdsSupPce.appendChild(input);
        });

        fenetreSupPce.style.display = 'flex';
    });
}

const btnModifCou = document.getElementById('btn_mdf_cou');
const fenetreCou = document.getElementById('fenetre_mdf_cou');
const formCou = document.getElementById('form_mdf_cou');
if (btnModifCou && fenetreCou && formCou) {
btnModifCou.addEventListener('click', () => {
        const ligne = gestionTableau.getLigne();

        if (!ligne) {
            alert("Sélectionne une ligne");
            return;
        }

        remplirFormulaireDepuisLigne(formCou, ligne);
        fenetreCou.style.display = 'flex';
    });
}

const btnSupprimerCou = document.getElementById('btn_spr_cou');
const inputIdSupCou = document.getElementById('spr_cou_id');
const fenetreSupCou = document.getElementById('fenetre_spr_cou');

if (btnSupprimerCou && inputIdSupCou && fenetreSupCou) {
btnSupprimerCou.addEventListener('click', () => {
    const id = gestionTableau.getId();

    if (!id) {
        alert("Sélectionne une ligne");
        return;
    }

    inputIdSupCou.value = id;

    fenetreSupCou.style.display = 'flex';
});
}

const btnModifDim = document.getElementById('btn_mdf_dim');
const fenetreDim = document.getElementById('fenetre_mdf_dim');
const formDim = document.getElementById('form_mdf_dim');
if (btnModifDim && fenetreDim && formDim) {
btnModifDim.addEventListener('click', () => {
        const ligne = gestionTableau.getLigne();

        if (!ligne) {
            alert("Sélectionne une ligne");
            return;
        }

        remplirFormulaireDepuisLigne(formDim, ligne);
        fenetreDim.style.display = 'flex';
    });
}

const btnSupprimerDim = document.getElementById('btn_spr_dim');
const inputIdSupDim = document.getElementById('spr_dim_id');
const fenetreSupDim = document.getElementById('fenetre_spr_dim');

if (btnSupprimerDim && inputIdSupDim && fenetreSupDim) {
btnSupprimerDim.addEventListener('click', () => {
    const id = gestionTableau.getId();

    if (!id) {
        alert("Sélectionne une ligne");
        return;
    }

    inputIdSupDim.value = id;

    fenetreSupDim.style.display = 'flex';
});
}

const btnModifEtp = document.getElementById('btn_mdf_etp');
const fenetreEtp = document.getElementById('fenetre_mdf_etp');
const formEtp = document.getElementById('form_mdf_etp');
if (btnModifEtp && fenetreEtp && formEtp) {
btnModifEtp.addEventListener('click', () => {
        const ligne = gestionTableau.getLigne();

        if (!ligne) {
            alert("Sélectionne une ligne");
            return;
        }

        remplirFormulaireDepuisLigne(formEtp, ligne);
        fenetreEtp.style.display = 'flex';
    });
}

const btnSupprimerEtp = document.getElementById('btn_spr_etp');
const inputIdSupEtp = document.getElementById('spr_etp_id');
const fenetreSupEtp = document.getElementById('fenetre_spr_etp');

if (btnSupprimerEtp && inputIdSupEtp && fenetreSupEtp) {
btnSupprimerEtp.addEventListener('click', () => {
    const id = gestionTableau.getId();

    if (!id) {
        alert("Sélectionne une ligne");
        return;
    }

    inputIdSupEtp.value = id;

    fenetreSupEtp.style.display = 'flex';
});
}
const btnModifMat = document.getElementById('btn_mdf_mat');
const fenetreMat = document.getElementById('fenetre_mdf_mat');
const formMat = document.getElementById('form_mdf_mat');
if (btnModifMat && fenetreMat && formMat) {
btnModifMat.addEventListener('click', () => {
        const ligne = gestionTableau.getLigne();

        if (!ligne) {
            alert("Sélectionne une ligne");
            return;
        }

        remplirFormulaireDepuisLigne(formMat, ligne);
        fenetreMat.style.display = 'flex';
    });
}

const btnSupprimerMat = document.getElementById('btn_spr_mat');
const inputIdSupMat = document.getElementById('spr_mat_id');
const fenetreSupMat = document.getElementById('fenetre_spr_mat');

if (btnSupprimerMat && inputIdSupMat && fenetreSupMat) {
btnSupprimerMat.addEventListener('click', () => {
    const id = gestionTableau.getId();

    if (!id) {
        alert("Sélectionne une ligne");
        return;
    }

    inputIdSupMat.value = id;

    fenetreSupMat.style.display = 'flex';
});
}

const btnModifTano = document.getElementById('btn_mdf_tano');
const fenetreTano = document.getElementById('fenetre_mdf_tano');
const formTano = document.getElementById('form_mdf_tano');
if (btnModifTano && fenetreTano && formTano) {
btnModifTano.addEventListener('click', () => {
        const ligne = gestionTableau.getLigne();

        if (!ligne) {
            alert("Sélectionne une ligne");
            return;
        }

        remplirFormulaireDepuisLigne(formTano, ligne);
        fenetreTano.style.display = 'flex';
    });
}

const btnSupprimerTano = document.getElementById('btn_spr_tano');
const inputIdSupTano = document.getElementById('spr_tano_id');
const fenetreSupTano = document.getElementById('fenetre_spr_tano');

if (btnSupprimerTano && inputIdSupTano && fenetreSupTano) {
btnSupprimerTano.addEventListener('click', () => {
    const id = gestionTableau.getId();

    if (!id) {
        alert("Sélectionne une ligne");
        return;
    }

    inputIdSupTano.value = id;

    fenetreSupTano.style.display = 'flex';
});
}

document.querySelectorAll('[data-open]').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-open');
            const fenetre = document.getElementById(id);

            if (fenetre) {
                fenetre.style.display = 'flex';
            }
        });
    });

    // fermer un modal
    document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', function () {
            const fenetre = this.closest('.fenetre');

            if (fenetre) {
                fenetre.style.display = 'none';
            }
        });
    });

    // réouvrir automatiquement le bon lot après reload
    const lotId = document.body.dataset.openLot;

    if (lotId) {
        const modal = document.getElementById('lot_' + lotId);

        if (modal) {
            modal.style.display = 'flex';
        }
    }
document.querySelectorAll('[data-close-defaut]').forEach(btn => {
    btn.addEventListener('click', function () {
        const fenetre = this.closest('.fenetre-defaut');

        if (fenetre) {
            fenetre.style.display = 'none';
        }
    });
});
const btnModifArt = document.getElementById('btn_mdf_art');
const fenetreArt = document.getElementById('fenetre_mdf_art');
const formArt = document.getElementById('form_mdf_art');
if (btnModifArt && fenetreArt && formArt) {
    btnModifArt.addEventListener('click', () => {
        const ligne = gestionTableau.getLigne();

        if (!ligne) {
            alert("Sélectionne une ligne");
            return;
        }

        remplirFormulaireDepuisLigne(formArt, ligne);
        fenetreArt.style.display = 'flex';
    });

}

const btnSupprimerArt = document.getElementById('btn_spr_art');
const inputIdArt = document.getElementById('spr_art_id');
const fenetreSupArt = document.getElementById('fenetre_spr_art');

if (btnSupprimerArt && inputIdArt && fenetreSupArt) {
btnSupprimerArt.addEventListener('click', () => {
    const id = gestionTableau.getId();

    if (!id) {
        alert("Sélectionne une ligne");
        return;
    }

    inputIdArt.value = id;

    fenetreSupArt.style.display = 'flex';
});
}

});
