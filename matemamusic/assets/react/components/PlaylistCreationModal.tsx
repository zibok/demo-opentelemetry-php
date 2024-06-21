import {Box, Button, CircularProgress, FormControl, Modal, TextField, Typography} from "@mui/material";
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
        <CircularProgress/>
    )
    const regularFormBody = (
        <FormControl>
            <TextField
                id="new-playlist-name"
                label="Playlist name"
                variant="outlined"
                required
                onChange={e => setName(e.target.value)}
            />
            <Button onClick={() => triggerPlaylistCreation(props.playlistOwnerId, name)}>Create</Button>
        </FormControl>
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
        <Modal
            open={props.open}
            onClose={props.onClose}
            aria-labelledby="modal-modal-title"
            aria-describedby="modal-modal-description"
        >
            <Box sx={modalStyle} component="form">
                <Typography id="modal-modal-title" variant="h6" component="h2">
                    Create a new playlist
                </Typography>
                {loading ? loadingFormBody : regularFormBody}
            </Box>
        </Modal>
    )
}