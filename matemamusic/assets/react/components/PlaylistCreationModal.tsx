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

export type PlaylistCreationModalProps = {
    open: boolean;
    onClose: (event: Object, reason: string) => void;
    playlistOwnerId: number;
}

export default function (props: PlaylistCreationModalProps) {
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
                        id="new-playlist-name"
                        label="Playlist name"
                        variant="outlined"
                        required
                        onChange={e => setName(e.target.value)}
                    />
                </FormControl>
            </DialogContent>
            <DialogActions>
                <Button onClick={() => triggerPlaylistCreation(props.playlistOwnerId, name)}>Create</Button>
                <Button onClick={() => props.onClose({}, "Cancelled")}>Cancel</Button>
            </DialogActions>
        </div>
    )

    const triggerPlaylistCreation= async (ownerId: number, name: string) => {
        setLoading(true);
        try {
            const response = await fetch(`/users/${ownerId}/createplaylist`, {
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
                props.onClose({}, "Successfully created a playlist")
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
            <DialogTitle>New playlist</DialogTitle>
            {loading ? loadingFormBody : regularFormBody}
        </Dialog>
    )
}