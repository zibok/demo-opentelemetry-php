import {Button, CircularProgress, Dialog, DialogActions, DialogContent, DialogTitle, FormControl, TextField} from "@mui/material";
import React, {useState} from "react";

const modalStyle = {
    position: 'absolute' as 'absolute',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    width: 400,
    bgcolor: 'background.paper',
    border: '2px solid #000',
    boxShadow: 24,
    p: 4,
};

export type FavlistCreationModalProps = {
    open: boolean;
    onClose: (event: Object, reason: string) => void;
    favlistOwnerId: number;
}

export default function (props: FavlistCreationModalProps) {
    const [loading, setLoading] = useState(false)
    const [name, setName] = useState("")

    const loadingFormBody = (
        <DialogContent>
            <CircularProgress/>
        </DialogContent>
    )
    const regularFormBody = (
        <div>
            <DialogContent>
                <FormControl>
                    <TextField
                        id="new-favlist-name"
                        label="Nom de la liste de favoris"
                        variant="outlined"
                        required
                        onChange={e => setName(e.target.value)}
                    />
                </FormControl>
            </DialogContent>
            <DialogActions>
            <Button onClick={() => props.onClose({}, "Cancelled")}>Annuler</Button>
            <Button onClick={() => triggerFavlistCreation(props.favlistOwnerId, name)} variant="outlined">Créer</Button>
            </DialogActions>
        </div>
    )

    const triggerFavlistCreation= async (ownerId: number, name: string) => {
        setLoading(true);
        try {
            const response = await fetch(`/users/${ownerId}/createfavlist`, {
                method: "POST",
                body: JSON.stringify({
                    name: name,
                    ownerId: ownerId,
                }),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            }).then(r => r.status);

            if (response === 204) {
                props.onClose({}, "Creation successful")
            }

            setLoading(false);

        } catch (err) {
            console.log(err)
        }
    }


    return (
        <Dialog disableEscapeKeyDown
            open={props.open}
            onClose={props.onClose}
            aria-labelledby="modal-modal-title"
            aria-describedby="modal-modal-description"
        >
            <DialogTitle>Nouvelle liste de favoris</DialogTitle>
            {loading ? loadingFormBody : regularFormBody}
        </Dialog>
    )
}