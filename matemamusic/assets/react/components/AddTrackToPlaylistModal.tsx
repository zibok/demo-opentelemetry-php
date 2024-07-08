import { Button, CircularProgress, Dialog, DialogActions, DialogContent, DialogTitle, FormControl, Link, TextField } from "@mui/material";
import React, { useEffect, useState } from "react";
import { Track } from "../types/Track";
import { DataGrid, GridColDef, GridRowSelectionModel } from "@mui/x-data-grid";

export type AddTrackToPlaylistModalProps = {
    open: boolean;
    onClose: (event: Object, reason: string) => void;
    playlistId: number;
};

const columns: GridColDef[] = [
    { field: 'trackId', headerName: 'ID', width: 50 },
    { field: 'title', headerName: 'Title', width: 315 },
    { field: 'author', headerName: 'Author', width: 250 },
    { 
        field: 'link', 
        headerName: 'Link', 
        renderCell: (params) => ( 
            <Link href={params.row.link}>Go</Link>
        ),
     },
];


export default function AddTrackToPlaylistModal(props: AddTrackToPlaylistModalProps) {
    const [loading, setLoading] = useState<boolean>(true);
    const [searchString, setSearchString] = useState<string>('');
    const [tracks, setTracks] = useState<Track[]>([]);
    const [selectedTracks, setSelectedTracks] = useState<Track[]>([]);


    useEffect(() => {searchTracks(searchString);}, [searchString]);

    const searchTracks = async (searchString: string) => {
        try {
            const response = await fetch(`/tracks/search?search=${searchString}`, {
                "headers": {
                    "Accept": "application/json"
                }
            }).then(r => r.json());
            
            setTracks(response.items);
            setLoading(false);
        } catch (err) {
            console.log(err)
        }
    };

    const addSelectedTracksToCurrentPlaylist = async () => {
        setLoading(true);
        const trackIds = selectedTracks.map(track => track.trackId);

        try {
            const response = await fetch(`/playlists/${props.playlistId}/tracks`, {
                method: "POST",
                body: JSON.stringify({
                    trackIds: trackIds,
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
   };

    const getRowId = (row: Track): number => row.trackId;

    const loadingFormBody = (
        <DialogContent>
            <CircularProgress/>
        </DialogContent>
    )

    const regularFormBody = (
        <DialogContent>
            <FormControl>
                <TextField
                    id="search-string"
                    label="Search"
                    variant="outlined"
                    required
                    onChange={e => setSearchString(e.target.value)}
                />
                <DataGrid 
                    autoHeight
                    checkboxSelection
                    columns={columns}
                    rows={tracks}
                    initialState={{
                        pagination: {
                            paginationModel: { page: 0, pageSize: 5 },
                        },
                    }}
                    pageSizeOptions={[5, 10]}
                    getRowId={getRowId}
                    
                    onRowSelectionModelChange={(rowSelectionModel: GridRowSelectionModel) => {
                        const selectedIDs = new Set(rowSelectionModel);
                        const selectedRows = tracks.filter((row) =>
                          selectedIDs.has(row.trackId),
                        );
              
                        setSelectedTracks(selectedRows);
                      }}
                />
            </FormControl>
        </DialogContent>
    )

    return (
        <Dialog
            open={props.open}
            onClose={props.onClose}
            aria-labelledby="modal-modal-title"
            aria-describedby="modal-modal-description"
        >
            <DialogTitle>Add track to playlist</DialogTitle>
            {loading ? loadingFormBody : regularFormBody}
            <DialogActions>
                <Button onClick={() => addSelectedTracksToCurrentPlaylist()}>Add tracks</Button>
            </DialogActions>
        </Dialog>
    );
}